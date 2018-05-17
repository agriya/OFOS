<?php
/**
 * AppModel
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;

class AppModel extends \Illuminate\Database\Eloquent\Model
{
    public function validate($data)
    {
        $translation_file_loader = new FileLoader(new Filesystem, __DIR__ . '../lang');
        $translator = new Translator($translation_file_loader, 'en');
        $factory = new ValidatorFactory($translator);
        $v = $factory->make($data, $this->rules);
        $v->passes();
        return $v->failed();
    }
    public function scopeFilter($query, $params = array())
    {
        global $capsule;
        if (isset($params['filter'])) {
            $filters = json_decode($params['filter'], true);
            if (isset($filters['where'])) {
                $query = $this->setWhere($query, $filters['where']);
            }
            if (isset($filters['limit']) && isset($filters['skip'])) {
                if($filters['limit'] == 'all'){
                    $filters['limit'] = $query->getModel()->count();
                }
                $query = $this->setLimit($query, $filters['limit']);
                $query = $this->setSkip($query, $filters['skip'], $filters['limit']);
            }
            if (isset($filters['fields'])) {
                $query = $this->setFields($query, $filters['fields']);
            }
            if (isset($filters['order'])) {
                $query = $this->setOrder($query, $filters['order']);
            }
            
            if (isset($filters['include'])) {
                $query = $this->setInclude($query, $filters['include']);
            }
        }
        return $query;
    }
    public function setInclude($query, $filters)
    {
		if(is_array($filters))
		{
			foreach ($filters as $model => $includes) {
				if (is_array($includes)) {
					$query->with(array($model => function ($query) use ($includes, $model) {
						foreach ($includes as $keyword => $valuefield) {
							if ($keyword === 'where') {
								$query = $this->setWhere($query, $valuefield);
							} elseif ($keyword === 'limit') {
								$query = $this->setLimit($query, $valuefield);
							} elseif ($keyword === 'skip') {
								$query = $this->setSkip($query, $valuefield, $includes['limit']);
							} elseif ($keyword === 'fields') {
								$query = $this->setFields($query, $valuefield);
							} elseif ($keyword === 'order') {
								$query = $this->setOrder($query, $valuefield);
							}
						}
					}));
                    if (!empty($includes['whereHas'])) {
                        $query->whereHas($model, function ($query) use ($includes) {
                            $query = $this->setWhere($query, $includes['whereHas']);
                        });
                    }
					foreach ($includes as $keyword => $valuefield) {
						if (is_numeric($keyword)) {
							$query = $query->with($model . '.' . $valuefield);
						} elseif (!in_array($keyword, array('whereHas', 'where', 'limit', 'skip', 'fields', 'order'))) {
							$this->setInclude($query, array($keyword => $valuefield));
						}
					}
				} else {
					$query->with($includes);
				}
			}
		}
        return $query;
    }
    public function setLimit($query, $limit)
    {
        $query->getModel()->setPerPage($limit);
        return $query;
    }
    public function setSkip($query, $skip, $limit)
    {
        $query->skip($skip)->take($limit);
        return $query;
    }
    public function setFields($query, $fields)
    {
        $positive_fields = $negative_fields = [];
        foreach ($fields as $key => $value) {
            if ($value) {
                $positive_fields[] = $key;
            } else {
                $negative_fields[] = $key;
            }
        }
        if (count($negative_fields) == count($fields)) {
            $table = $query->getQuery()->from;
            $fields = $capsule::schema()->getColumnListing($table);
            foreach ($negative_fields as $nagative_field) {
                $key = array_search($nagative_field, $fields);
                if ($key) {
                    unset($fields[$key]);
                }
            }
            $query->select($fields);
        } else {
            $query->select($positive_fields);
        }
        return $query;
    }
    public function setOrder($query, $orders)
    {
        $sorts = array();
        if (!is_array($orders)) {
            $sort_sortby = explode(' ', $orders);
            array_push($sorts, $sort_sortby);
        } else {
            foreach ($orders as $order) {
                $sort_sortby = explode(' ', $order);
                array_push($sorts, $sort_sortby);
            }
        }
        foreach ($sorts as $sort) {
            if (empty($query->getQuery()->joins)) {
                $query->orderBy($sort[0], $sort[1]);
            }
            if (!empty($query->getQuery()->joins)) {
                if (strpos($sort[0], '.')) {
                    $query->orderBy(str_replace('.', '_', $sort[0]), $sort[1]);
                } else {
                    $query->orderBy($query->getQuery()->from . '.' . $sort[0], $sort[1]);
                }
            }
        }
        return $query;
    }
    public function setWhere($query, $where)
    {
        foreach ($where as $key => $value) {
            if ($key == 'OR') {
                $query = $query->where(function ($query) use ($key, $value) {
                    $i = 0;
                    foreach ($value as $k => $v) {
                        if (empty($i)) {
                            $query = $query->where(function ($q) use ($k, $v) {
                                if (in_array($k, array('OR', 'AND'))) {
                                    $q = $this->setWhere($q, array($k => $v));
                                } else {
                                    $q = $this->setSubWhere( $q, $k, $v);
                                }
                                return $q;
                            });
                        } else {
                            $query = $query->orWhere(function ($q) use ($k, $v) {
                                if (in_array($k, array('OR', 'AND'))) {
                                    $q = $this->setWhere( $q, array($k => $v));
                                } else {
                                    $q = $this->setSubWhere( $q, $k, $v);
                                }
                                return $q;
                            });
                        }
                        $i++;
                    }
                });
            } elseif ($key == 'AND') {
                $query = $query->where(function ($query) use ($key, $value) {
                    foreach ($value as $k => $v) {
                        if (in_array($k, array('OR', 'AND'))) {
                            $query = $this->setWhere( $query, array($k => $v));
                        } else {
                            $query = $this->setSubWhere($query, $k, $v);
                        }
                    }
                });
            } else {
                $query = $this->setSubWhere($query, $key, $value);
            }
        }
        return $query;
    }
    public function setSubWhere($query, $key, $value)
    {
        if (is_array($value)) {
            $operatorText = key($value);
            if (in_array($operatorText, array('eq', '=', 'neq', 'gt', 'gte', 'lt', 'lte', 'like', 'nlike', 'ilike', 'nilike', 'regexp'))) {
                if ($operatorText == 'eq' || $operatorText == '=') {
                    $operator = '=';
                } elseif ($operatorText == 'neq') {
                    $operator = '!=';
                } elseif ($operatorText == 'gt') {
                    $operator = '>';
                } elseif ($operatorText == 'gte') {
                    $operator = '>=';
                } elseif ($operatorText == 'lt') {
                    $operator = '<';
                } elseif ($operatorText == 'lte') {
                    $operator = '<=';
                } elseif ($operatorText == 'like') {
                    $operator = 'like';
                } elseif ($operatorText == 'nlike') {
                    $operator = 'not like';
                } elseif ($operatorText == 'ilike') {
                    $operator = 'ilike';
                } elseif ($operatorText == 'nilike') {
                    $operator = 'not ilike';
                } elseif ($operatorText == 'regexp') {
                    $operator = 'regexp';
                }
                $query->where($key, $operator, current($value));
            } elseif ($operatorText == 'between') {
                // @TODO
                $query->whereBetween($key, $value[$operatorText]);
            } elseif ($operatorText == 'inq') {
                $query->whereIn($key, $value[$operatorText]);
            } elseif ($operatorText == 'nin') {
                $query->whereNotIn($key, $value[$operatorText]);
            } elseif (in_array($operatorText, array('OR', 'AND'))) {
                $query = $this->setWhere($query, array($operatorText => $value));
            } else {
                $query->where($operatorText, $value);
            }
        } else {
            $query->where($key, $value);
        }
        return $query;
    }
}
