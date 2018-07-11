--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.4
-- Dumped by pg_dump version 9.5.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: attachments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE attachments (
    id bigint DEFAULT nextval('attachments_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    class character varying(255) NOT NULL,
    foreign_id bigint NOT NULL,
    filename character varying(255) NOT NULL,
    dir character varying(255) NOT NULL,
    mimetype character varying(255),
    filesize bigint,
    height bigint DEFAULT (0)::bigint NOT NULL,
    width bigint DEFAULT (0)::bigint NOT NULL,
    CONSTRAINT attachments_foreign_id_check CHECK ((foreign_id >= 0))
);


--
-- Name: banned_ips_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE banned_ips_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: banned_ips; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE banned_ips (
    id bigint DEFAULT nextval('banned_ips_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    address character varying(255) DEFAULT NULL::character varying,
    range text,
    reason character varying(255) DEFAULT NULL::character varying,
    redirect character varying(255) DEFAULT NULL::character varying,
    thetime integer NOT NULL,
    timespan integer NOT NULL
);


--
-- Name: cart_addons_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE cart_addons_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cart_addons; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE cart_addons (
    id bigint DEFAULT nextval('cart_addons_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    cart_id bigint,
    restaurant_addon_id bigint NOT NULL,
    restaurant_addon_item_id bigint,
    restaurant_menu_addon_price_id bigint NOT NULL,
    price double precision NOT NULL
);


--
-- Name: carts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE carts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: carts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE carts (
    id bigint DEFAULT nextval('carts_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    cookie_id character varying(255) NOT NULL,
    user_id bigint,
    restaurant_id bigint NOT NULL,
    restaurant_menu_id bigint NOT NULL,
    restaurant_menu_price_id bigint NOT NULL,
    quantity integer NOT NULL,
    price double precision NOT NULL,
    total_price double precision NOT NULL
);


--
-- Name: cities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE cities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE cities (
    id bigint DEFAULT nextval('cities_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    country_id bigint DEFAULT (0)::bigint NOT NULL,
    state_id bigint DEFAULT (0)::bigint NOT NULL,
    name character varying(255) NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: contacts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE contacts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: contacts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE contacts (
    id bigint DEFAULT nextval('contacts_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    phone character varying(50) NOT NULL,
    subject character varying(255) NOT NULL,
    message text NOT NULL,
    ip_id bigint
);


--
-- Name: countries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE countries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: countries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE countries (
    id integer DEFAULT nextval('countries_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    name character varying(255) NOT NULL,
    iso2 character varying(2) DEFAULT 'NULL'::character varying,
    iso3 character varying(3) DEFAULT 'NULL'::character varying,
    continent character varying(2) DEFAULT 'NULL'::character varying,
    currency character varying(3) DEFAULT 'NULL'::character varying,
    currencyname character varying(20) DEFAULT 'NULL'::character varying,
    phone character varying(10) DEFAULT 'NULL'::character varying,
    postalcodeformat character varying(20) DEFAULT 'NULL'::character varying,
    postalcoderegex character varying(20) DEFAULT 'NULL'::character varying,
    languages character varying(200)
);


--
-- Name: coupons_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE coupons_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: coupons; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE coupons (
    id bigint DEFAULT nextval('coupons_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    restaurant_id bigint NOT NULL,
    coupon_code character varying(255) NOT NULL,
    discount double precision DEFAULT '0'::double precision NOT NULL,
    is_flat_discount_in_amount boolean DEFAULT true NOT NULL,
    no_of_quantity_allowed bigint,
    no_of_quantity_used bigint DEFAULT '0'::bigint NOT NULL,
    validity_start_date date,
    validity_end_date date,
    maximum_discount_amount double precision DEFAULT '0'::double precision NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: cuisines_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE cuisines_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cuisines; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE cuisines (
    id bigint DEFAULT nextval('cuisines_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    slug character varying(265) NOT NULL
);


--
-- Name: device_details_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE device_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: device_details; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE device_details (
    id bigint DEFAULT nextval('device_details_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint,
    appname character varying(255),
    appversion character varying(255),
    deviceuid text,
    devicetoken text,
    devicename character varying(255),
    devicemodel character varying,
    deviceversion character varying(255),
    pushbadge character varying(255),
    pushalert character varying(255),
    pushsound character varying(255),
    development character varying(255),
    status character varying(255),
    latitude double precision NOT NULL,
    longitude double precision NOT NULL,
    devicetype integer NOT NULL
);


--
-- Name: COLUMN device_details.devicetype; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN device_details.devicetype IS '1. Android  2. iPhone';


--
-- Name: email_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE email_templates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: email_templates; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE email_templates (
    id bigint DEFAULT nextval('email_templates_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL,
    description text NOT NULL,
    from_email character varying(255) NOT NULL,
    reply_to_email character varying(255) NOT NULL,
    subject character varying(255) NOT NULL,
    email_variables character varying(500) NOT NULL,
    html_email_content text,
    text_email_content text,
    display_name character varying(255),
    to_email text DEFAULT '##TO_EMAIL##'::text NOT NULL,
    is_admin_email boolean DEFAULT false NOT NULL,
    plugin character varying(255),
    is_html boolean DEFAULT false NOT NULL
);


--
-- Name: ips_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE ips_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ips; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE ips (
    id bigint DEFAULT nextval('ips_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    ip character varying(255) NOT NULL,
    host character varying(255) NOT NULL,
    city_id bigint,
    state_id bigint,
    country_id bigint,
    latitude double precision,
    longitude double precision
);


--
-- Name: languages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE languages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: languages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE languages (
    id bigint DEFAULT nextval('languages_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL,
    iso2 character(2) NOT NULL,
    iso3 character(3) NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: money_transfer_account_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE money_transfer_account_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: money_transfer_accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE money_transfer_accounts (
    id bigint DEFAULT nextval('money_transfer_account_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    account text NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    is_primary boolean DEFAULT false NOT NULL
);


--
-- Name: oauth_clients_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE oauth_clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: oauth_clients; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE oauth_clients (
    id integer DEFAULT nextval('oauth_clients_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL,
    api_key character varying(80) NOT NULL,
    api_secret character varying(80) NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: order_item_addons_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE order_item_addons_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: order_item_addons; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE order_item_addons (
    id bigint DEFAULT nextval('order_item_addons_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    order_id bigint NOT NULL,
    order_item_id bigint NOT NULL,
    restaurant_addon_id bigint,
    restaurant_menu_addon_price_id bigint,
    price double precision NOT NULL
);


--
-- Name: order_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE order_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: order_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE order_items (
    id bigint DEFAULT nextval('order_items_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    order_id bigint NOT NULL,
    restaurant_menu_id bigint NOT NULL,
    restaurant_menu_price_id bigint,
    quantity integer NOT NULL,
    price double precision NOT NULL,
    total_price double precision NOT NULL
);


--
-- Name: order_statuses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE order_statuses (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL
);


--
-- Name: order_statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE order_statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: order_statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE order_statuses_id_seq OWNED BY order_statuses.id;


--
-- Name: orders_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: orders; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE orders (
    id bigint DEFAULT nextval('orders_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    restaurant_id bigint NOT NULL,
    restaurant_branch_id bigint,
    restaurant_delivery_person_id bigint,
    order_status_id integer DEFAULT 0 NOT NULL,
    payment_gateway_id bigint DEFAULT '0'::bigint NOT NULL,
    gateway_id bigint DEFAULT '0'::bigint NOT NULL,
    total_price double precision DEFAULT '0'::double precision NOT NULL,
    delivery_charge double precision DEFAULT '0'::double precision NOT NULL,
    sales_tax double precision DEFAULT '0'::double precision NOT NULL,
    site_fee double precision DEFAULT '0'::double precision NOT NULL,
    user_address_id bigint,
    address character varying(255),
    city_id bigint,
    state_id bigint,
    country_id bigint,
    latitude double precision DEFAULT '0'::double precision NOT NULL,
    longitude double precision DEFAULT '0'::double precision NOT NULL,
    zip_code character varying(50),
    comment text,
    later_delivery_date timestamp without time zone,
    delivered_date timestamp without time zone,
    is_as_soon_as_delivery boolean DEFAULT false NOT NULL,
    is_pickup_or_delivery boolean DEFAULT false NOT NULL,
    success_url character varying(255),
    cancel_url character varying(255),
    paypal_pay_key character varying(255),
    zazpay_pay_key character varying(255),
    coupon_id bigint,
    discount_amount double precision DEFAULT '0'::double precision NOT NULL,
    track_id text
);


--
-- Name: pages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE pages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE pages (
    id bigint DEFAULT nextval('pages_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    title character varying(255) NOT NULL,
    slug character varying(265) NOT NULL,
    content text NOT NULL,
    meta_keywords character varying(255),
    meta_description text,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: payment_gateway_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE payment_gateway_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payment_gateway_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE payment_gateway_settings (
    id bigint DEFAULT nextval('payment_gateway_settings_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    payment_gateway_id integer NOT NULL,
    name character varying(256) NOT NULL,
    label character varying(512) NOT NULL,
    description text NOT NULL,
    type character varying(8),
    options text NOT NULL,
    test_mode_value text,
    live_mode_value text
);


--
-- Name: payment_gateways_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE payment_gateways_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payment_gateways; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE payment_gateways (
    id bigint DEFAULT nextval('payment_gateways_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    name character varying(255) NOT NULL,
    display_name character varying(255) NOT NULL,
    description text NOT NULL,
    gateway_fees double precision,
    is_test_mode boolean DEFAULT true NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    is_enable_for_wallet boolean DEFAULT false NOT NULL,
    plugin character varying(255)
);


--
-- Name: provider_users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE provider_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: provider_users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE provider_users (
    id bigint DEFAULT nextval('provider_users_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    provider_id bigint NOT NULL,
    foreign_id character varying(255),
    profile_picture_url character varying(255),
    access_token character varying(255) NOT NULL,
    access_token_secret character varying(255),
    is_connected boolean DEFAULT true NOT NULL
);


--
-- Name: providers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE providers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: providers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE providers (
    id bigint DEFAULT nextval('providers_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(265) NOT NULL,
    secret_key character varying(255),
    api_key character varying(255),
    icon_class character varying(255),
    button_class character varying(255),
    display_order bigint,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: push_notifications_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE push_notifications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: push_notifications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE push_notifications (
    id bigint DEFAULT nextval('push_notifications_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_device_id bigint NOT NULL,
    message_type character varying NOT NULL,
    message text NOT NULL
);


--
-- Name: restaurant_addon_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_addon_items (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    restaurant_addon_id bigint NOT NULL,
    name character varying NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: restaurant_addon_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_addon_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_addon_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE restaurant_addon_items_id_seq OWNED BY restaurant_addon_items.id;


--
-- Name: restaurant_addons_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_addons_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_addons; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_addons (
    id bigint DEFAULT nextval('restaurant_addons_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    restaurant_id bigint NOT NULL,
    restaurant_category_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    is_multiple boolean DEFAULT true NOT NULL
);


--
-- Name: restaurant_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_categories (
    id bigint DEFAULT nextval('restaurant_categories_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    restaurant_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    display_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    slug character varying(255)
);


--
-- Name: restaurant_cuisines; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_cuisines (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    restaurant_id bigint NOT NULL,
    cuisine_id bigint NOT NULL
);


--
-- Name: restaurant_cuisine_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_cuisine_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_cuisine_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE restaurant_cuisine_id_seq OWNED BY restaurant_cuisines.id;


--
-- Name: restaurant_delivery_person_orders_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_delivery_person_orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_delivery_person_orders; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_delivery_person_orders (
    id bigint DEFAULT nextval('restaurant_delivery_person_orders_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    order_id bigint NOT NULL,
    restaurant_id bigint NOT NULL,
    restaurant_branch_id bigint DEFAULT (0)::bigint NOT NULL,
    restaurant_supervisor_id bigint DEFAULT (0)::bigint NOT NULL,
    restaurant_delivery_person_id bigint NOT NULL,
    comments text,
    is_delivered boolean DEFAULT false NOT NULL
);


--
-- Name: restaurant_delivery_persons_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_delivery_persons_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_delivery_persons; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_delivery_persons (
    id bigint DEFAULT nextval('restaurant_delivery_persons_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    restaurant_id bigint,
    restaurant_branch_id bigint,
    restaurant_supervisor_id bigint,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: restaurant_menu_addon_prices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_menu_addon_prices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_menu_addon_prices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_menu_addon_prices (
    id bigint DEFAULT nextval('restaurant_menu_addon_prices_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    restaurant_menu_id bigint NOT NULL,
    restaurant_addon_id bigint DEFAULT (0)::bigint NOT NULL,
    restaurant_addon_item_id bigint NOT NULL,
    price double precision NOT NULL,
    is_free boolean DEFAULT true NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: restaurant_menu_prices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_menu_prices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_menu_prices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_menu_prices (
    id bigint DEFAULT nextval('restaurant_menu_prices_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    restaurant_menu_id bigint NOT NULL,
    price_type_id character varying NOT NULL,
    price_type_name character varying(250),
    price double precision NOT NULL
);


--
-- Name: COLUMN restaurant_menu_prices.price_type_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN restaurant_menu_prices.price_type_id IS '1 - Fixed, 2 - Size, 3 - Slice';


--
-- Name: restaurant_menus_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_menus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_menus; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_menus (
    id bigint DEFAULT nextval('restaurant_menus_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    cuisine_id bigint,
    restaurant_id bigint NOT NULL,
    restaurant_category_id bigint NOT NULL,
    menu_type_id integer DEFAULT 1 NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    display_order integer DEFAULT 0 NOT NULL,
    is_addon boolean DEFAULT false NOT NULL,
    is_popular boolean DEFAULT false NOT NULL,
    is_spicy boolean DEFAULT false NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    color character varying(255),
    stock bigint DEFAULT '0'::bigint NOT NULL,
    sold_quantity bigint DEFAULT '0'::bigint NOT NULL,
    slug character varying(255),
    ordered_menu_count bigint DEFAULT '0'::bigint NOT NULL
);


--
-- Name: COLUMN restaurant_menus.menu_type_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN restaurant_menus.menu_type_id IS '1- Veg, 2- Non-Veg';


--
-- Name: restaurant_reviews_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_reviews_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_reviews; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_reviews (
    id bigint DEFAULT nextval('restaurant_reviews_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    order_id bigint NOT NULL,
    restaurant_id bigint NOT NULL,
    rating integer NOT NULL,
    message text NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: restaurant_supervisors_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_supervisors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_supervisors; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_supervisors (
    id bigint DEFAULT nextval('restaurant_supervisors_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    restaurant_id bigint,
    restaurant_branch_id bigint,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: restaurant_timings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurant_timings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurant_timings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurant_timings (
    id bigint DEFAULT nextval('restaurant_timings_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    restaurant_id bigint NOT NULL,
    day character varying(100) NOT NULL,
    period_type integer NOT NULL,
    start_time time without time zone NOT NULL,
    end_time time without time zone NOT NULL
);


--
-- Name: restaurants_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE restaurants_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: restaurants; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE restaurants (
    id bigint DEFAULT nextval('restaurants_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    parent_id bigint,
    name character varying(255) NOT NULL,
    slug character varying(265) NOT NULL,
    phone character varying(20),
    mobile character varying(15) NOT NULL,
    fax character varying(20),
    contact_name character varying(150) NOT NULL,
    contact_phone character varying(20),
    website character varying(100),
    address character varying(255) NOT NULL,
    address1 character varying(255),
    city_id bigint NOT NULL,
    state_id bigint NOT NULL,
    country_id bigint NOT NULL,
    latitude double precision NOT NULL,
    longitude double precision NOT NULL,
    hash text,
    zip_code character varying(30),
    sales_tax double precision DEFAULT '0'::double precision NOT NULL,
    minimum_order_for_booking double precision DEFAULT '0'::double precision NOT NULL,
    estimated_time_to_delivery integer NOT NULL,
    delivery_charge double precision NOT NULL,
    delivery_miles integer NOT NULL,
    total_reviews bigint DEFAULT '0'::bigint NOT NULL,
    avg_rating double precision DEFAULT '0'::double precision NOT NULL,
    total_orders bigint DEFAULT '0'::bigint NOT NULL,
    total_revenue double precision DEFAULT '0'::double precision NOT NULL,
    is_allow_users_to_door_delivery_order boolean DEFAULT false NOT NULL,
    is_allow_users_to_pickup_order boolean DEFAULT false NOT NULL,
    is_allow_users_to_preorder boolean DEFAULT true NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    is_closed boolean DEFAULT true NOT NULL,
    is_delivered_by_own boolean DEFAULT false NOT NULL,
    mobile_code character varying(255)
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE roles (
    id bigint DEFAULT nextval('roles_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(50) NOT NULL,
    is_active boolean NOT NULL
);


--
-- Name: setting_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE setting_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: setting_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE setting_categories (
    id bigint DEFAULT nextval('setting_categories_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(200) NOT NULL,
    description text,
    plugin character varying(255)
);


--
-- Name: settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE settings (
    id bigint DEFAULT nextval('settings_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    setting_category_id bigint,
    name character varying(255) NOT NULL,
    value text,
    description text,
    type character varying(8) NOT NULL,
    label character varying(255) NOT NULL,
    display_order integer NOT NULL,
    options text,
    plugin character varying(255),
    is_front_end_access boolean DEFAULT false NOT NULL
);


--
-- Name: states_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE states_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: states; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE states (
    id bigint DEFAULT nextval('states_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    country_id bigint NOT NULL,
    name character varying(80) NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    CONSTRAINT states_country_id_check CHECK ((country_id >= 0))
);


--
-- Name: sudopay_ipn_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE sudopay_ipn_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sudopay_ipn_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE sudopay_ipn_logs (
    id bigint DEFAULT nextval('sudopay_ipn_logs_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    ip bigint NOT NULL,
    post_variable text NOT NULL
);


--
-- Name: sudopay_payment_gateways; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE sudopay_payment_gateways (
    id bigint NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    sudopay_gateway_name character varying(255) NOT NULL,
    sudopay_gateway_id bigint NOT NULL,
    sudopay_payment_group_id bigint NOT NULL,
    sudopay_gateway_details text NOT NULL,
    is_marketplace_supported boolean NOT NULL
);


--
-- Name: sudopay_payment_gateways_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE sudopay_payment_gateways_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sudopay_payment_gateways_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE sudopay_payment_gateways_id_seq OWNED BY sudopay_payment_gateways.id;


--
-- Name: sudopay_payment_gateways_users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE sudopay_payment_gateways_users (
    id bigint NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    sudopay_payment_gateway_id bigint NOT NULL
);


--
-- Name: sudopay_payment_gateways_users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE sudopay_payment_gateways_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sudopay_payment_gateways_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE sudopay_payment_gateways_users_id_seq OWNED BY sudopay_payment_gateways_users.id;


--
-- Name: sudopay_payment_groups; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE sudopay_payment_groups (
    id bigint NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    sudopay_group_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    thumb_url text NOT NULL
);


--
-- Name: sudopay_payment_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE sudopay_payment_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sudopay_payment_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE sudopay_payment_groups_id_seq OWNED BY sudopay_payment_groups.id;


--
-- Name: sudopay_transaction_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE sudopay_transaction_logs (
    id bigint NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    class character varying(50) NOT NULL,
    foreign_id bigint NOT NULL,
    sudopay_pay_key character varying(255) NOT NULL,
    merchant_id bigint NOT NULL,
    gateway_id bigint NOT NULL,
    status character varying(50) NOT NULL,
    payment_type character varying(50) NOT NULL,
    buyer_id bigint NOT NULL,
    buyer_email character varying(255) NOT NULL,
    buyer_address character varying(255) NOT NULL,
    amount double precision DEFAULT (0)::double precision NOT NULL,
    payment_id bigint DEFAULT (0)::bigint NOT NULL
);


--
-- Name: sudopay_transaction_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE sudopay_transaction_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sudopay_transaction_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE sudopay_transaction_logs_id_seq OWNED BY sudopay_transaction_logs.id;


--
-- Name: transaction_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE transaction_types (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    name character varying(255) NOT NULL,
    is_credit boolean NOT NULL,
    is_credit_to_other_user boolean NOT NULL,
    is_credit_to_admin boolean NOT NULL,
    message character varying(255),
    message_for_other_user character varying(255),
    message_for_admin character varying(255),
    transaction_variables text
);


--
-- Name: transaction_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE transaction_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transaction_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE transaction_types_id_seq OWNED BY transaction_types.id;


--
-- Name: transactions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE transactions (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    other_user_id bigint DEFAULT '0'::bigint NOT NULL,
    restaurant_id bigint DEFAULT '0'::bigint NOT NULL,
    amount double precision NOT NULL,
    foreign_id bigint NOT NULL,
    class character varying(255) NOT NULL,
    transaction_type_id integer NOT NULL,
    payment_gateway_id integer,
    gateway_fees double precision
);


--
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE transactions_id_seq OWNED BY transactions.id;


--
-- Name: user_add_wallet_amounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_add_wallet_amounts (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    description text,
    amount double precision DEFAULT 0.00 NOT NULL,
    payment_gateway_id bigint,
    sudopay_gateway_id bigint,
    sudopay_revised_amount double precision DEFAULT 0.00 NOT NULL,
    sudopay_token character varying(255),
    is_success boolean DEFAULT false NOT NULL
);


--
-- Name: user_add_wallet_amounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_add_wallet_amounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_add_wallet_amounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_add_wallet_amounts_id_seq OWNED BY user_add_wallet_amounts.id;


--
-- Name: user_address_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_address_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_addresses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_addresses (
    id bigint DEFAULT nextval('user_address_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    building_address character varying(255),
    address2 character varying(255),
    landmark character varying(255),
    city_id bigint NOT NULL,
    state_id bigint NOT NULL,
    country_id bigint NOT NULL,
    zip_code character varying(30),
    latitude numeric(10,6) NOT NULL,
    longitude numeric(10,6) NOT NULL,
    hash character varying(255),
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: user_cash_withdrawals_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_cash_withdrawals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_cash_withdrawals; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_cash_withdrawals (
    id bigint DEFAULT nextval('user_cash_withdrawals_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    money_transfer_account_id bigint NOT NULL,
    amount double precision NOT NULL,
    remark text,
    status integer DEFAULT 0 NOT NULL
);


--
-- Name: user_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE user_tokens (
    id bigint DEFAULT nextval('user_tokens_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    oauth_client_id bigint NOT NULL,
    token character varying(255) NOT NULL,
    expires timestamp without time zone NOT NULL
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE users (
    id bigint DEFAULT nextval('users_id_seq'::regclass) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    username character varying(255) NOT NULL,
    email character varying(256) NOT NULL,
    password character varying(256) NOT NULL,
    role_id integer DEFAULT 2 NOT NULL,
    provider_id bigint DEFAULT (0)::bigint NOT NULL,
    first_name character varying(150),
    last_name character varying(150),
    gender_id smallint,
    dob date,
    about_me text,
    address character varying(255),
    address1 character varying(255),
    phone character varying(20),
    mobile character varying(15),
    city_id bigint DEFAULT (0)::bigint NOT NULL,
    state_id bigint DEFAULT (0)::bigint NOT NULL,
    country_id bigint DEFAULT (0)::bigint NOT NULL,
    latitude numeric(10,6),
    longitude numeric(10,6),
    available_wallet_amount double precision DEFAULT (0)::double precision NOT NULL,
    total_orders bigint DEFAULT (0)::bigint NOT NULL,
    total_reviews bigint DEFAULT (0)::bigint NOT NULL,
    zip_code character varying(50),
    last_logged_in_time timestamp without time zone,
    last_login_ip_id character varying(30) DEFAULT '0'::character varying NOT NULL,
    is_email_confirmed boolean DEFAULT false NOT NULL,
    is_agree_terms_conditions boolean DEFAULT false NOT NULL,
    is_subscribed boolean DEFAULT false NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    is_created_from_order_page boolean DEFAULT false NOT NULL,
    mobile_code character varying(255)
);


--
-- Name: wallet_transaction_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE wallet_transaction_logs (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    amount double precision DEFAULT 0.00 NOT NULL,
    foreign_id bigint NOT NULL,
    class character varying(255) NOT NULL,
    status character varying(255) NOT NULL,
    payment_type character varying(255) NOT NULL
);


--
-- Name: wallet_transaction_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE wallet_transaction_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: wallet_transaction_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE wallet_transaction_logs_id_seq OWNED BY wallet_transaction_logs.id;


--
-- Name: wallets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE wallets (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    user_id bigint NOT NULL,
    amount double precision NOT NULL,
    payment_gateway_id smallint DEFAULT '0'::smallint NOT NULL,
    gateway_id bigint DEFAULT '0'::bigint NOT NULL,
    is_payment_completed boolean DEFAULT false NOT NULL,
    success_url character varying,
    cancel_url character varying,
    paypal_pay_key character varying(255),
    zazpay_pay_key character varying(255)
);


--
-- Name: wallets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE wallets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: wallets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE wallets_id_seq OWNED BY wallets.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_statuses ALTER COLUMN id SET DEFAULT nextval('order_statuses_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_addon_items ALTER COLUMN id SET DEFAULT nextval('restaurant_addon_items_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_cuisines ALTER COLUMN id SET DEFAULT nextval('restaurant_cuisine_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY sudopay_payment_gateways ALTER COLUMN id SET DEFAULT nextval('sudopay_payment_gateways_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY sudopay_payment_gateways_users ALTER COLUMN id SET DEFAULT nextval('sudopay_payment_gateways_users_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY sudopay_payment_groups ALTER COLUMN id SET DEFAULT nextval('sudopay_payment_groups_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY sudopay_transaction_logs ALTER COLUMN id SET DEFAULT nextval('sudopay_transaction_logs_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY transaction_types ALTER COLUMN id SET DEFAULT nextval('transaction_types_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY transactions ALTER COLUMN id SET DEFAULT nextval('transactions_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_add_wallet_amounts ALTER COLUMN id SET DEFAULT nextval('user_add_wallet_amounts_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY wallet_transaction_logs ALTER COLUMN id SET DEFAULT nextval('wallet_transaction_logs_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY wallets ALTER COLUMN id SET DEFAULT nextval('wallets_id_seq'::regclass);


--
-- Data for Name: attachments; Type: TABLE DATA; Schema: public; Owner: -
--

COPY attachments (id, created_at, updated_at, class, foreign_id, filename, dir, mimetype, filesize, height, width) FROM stdin;
\.


--
-- Name: attachments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('attachments_id_seq', 1, true);


--
-- Data for Name: banned_ips; Type: TABLE DATA; Schema: public; Owner: -
--

COPY banned_ips (id, created_at, updated_at, address, range, reason, redirect, thetime, timespan) FROM stdin;
\.


--
-- Name: banned_ips_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('banned_ips_id_seq', 1, false);


--
-- Data for Name: cart_addons; Type: TABLE DATA; Schema: public; Owner: -
--

COPY cart_addons (id, created_at, updated_at, cart_id, restaurant_addon_id, restaurant_addon_item_id, restaurant_menu_addon_price_id, price) FROM stdin;
\.


--
-- Name: cart_addons_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('cart_addons_id_seq', 1, true);


--
-- Data for Name: carts; Type: TABLE DATA; Schema: public; Owner: -
--

COPY carts (id, created_at, updated_at, cookie_id, user_id, restaurant_id, restaurant_menu_id, restaurant_menu_price_id, quantity, price, total_price) FROM stdin;
\.


--
-- Name: carts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('carts_id_seq', 26, true);


--
-- Data for Name: cities; Type: TABLE DATA; Schema: public; Owner: -
--

COPY cities (id, created_at, updated_at, country_id, state_id, name, is_active) FROM stdin;
\.


--
-- Name: cities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('cities_id_seq', 1, true);


--
-- Data for Name: contacts; Type: TABLE DATA; Schema: public; Owner: -
--

COPY contacts (id, created_at, updated_at, first_name, last_name, email, phone, subject, message, ip_id) FROM stdin;
\.


--
-- Name: contacts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('contacts_id_seq', 1, true);


--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: -
--

COPY countries (id, created_at, updated_at, name, iso2, iso3, continent, currency, currencyname, phone, postalcodeformat, postalcoderegex, languages) FROM stdin;
1	\N	\N	Afghanistan	AF	AFG	AS	AFN	Afghani	93			fa-AF,ps,uz-AF,tk
2	\N	\N	Aland Islands	AX	ALA	EU	EUR	Euro	+358-18			sv-AX
3	\N	\N	Albania	AL	ALB	EU	ALL	Lek	355			sq,el
4	\N	\N	Algeria	DZ	DZA	AF	DZD	Dinar	213	#####	^(d{5})$	ar-DZ
5	\N	\N	American Samoa	AS	ASM	OC	USD	Dollar	+1-684			en-AS,sm,to
6	\N	\N	Andorra	AD	AND	EU	EUR	Euro	376	AD###	^(?:AD)*(d{3})$	ca
7	\N	\N	Angola	AO	AGO	AF	AOA	Kwanza	244			pt-AO
8	\N	\N	Anguilla	AI	AIA	NA	XCD	Dollar	+1-264			en-AI
9	\N	\N	Antarctica	AQ	ATA	AN						
10	\N	\N	Antigua and Barbuda	AG	ATG	NA	XCD	Dollar	+1-268			en-AG
11	\N	\N	Argentina	AR	ARG	SA	ARS	Peso	54	@####@@@	^([A-Z]d{4}[A-Z]{3})	es-AR,en,it,de,fr,gn
12	\N	\N	Armenia	AM	ARM	AS	AMD	Dram	374	######	^(d{6})$	hy
13	\N	\N	Aruba	AW	ABW	NA	AWG	Guilder	297			nl-AW,es,en
14	\N	\N	Australia	AU	AUS	OC	AUD	Dollar	61	####	^(d{4})$	en-AU
15	\N	\N	Austria	AT	AUT	EU	EUR	Euro	43	####	^(d{4})$	de-AT,hr,hu,sl
16	\N	\N	Azerbaijan	AZ	AZE	AS	AZN	Manat	994	AZ ####	^(?:AZ)*(d{4})$	az,ru,hy
17	\N	\N	Bahamas	BS	BHS	NA	BSD	Dollar	+1-242			en-BS
18	\N	\N	Bahrain	BH	BHR	AS	BHD	Dinar	973	####|###	^(d{3}d?)$	ar-BH,en,fa,ur
19	\N	\N	Bangladesh	BD	BGD	AS	BDT	Taka	880	####	^(d{4})$	bn-BD,en
20	\N	\N	Barbados	BB	BRB	NA	BBD	Dollar	+1-246	BB#####	^(?:BB)*(d{5})$	en-BB
21	\N	\N	Belarus	BY	BLR	EU	BYR	Ruble	375	######	^(d{6})$	be,ru
22	\N	\N	Belgium	BE	BEL	EU	EUR	Euro	32	####	^(d{4})$	nl-BE,fr-BE,de-BE
23	\N	\N	Belize	BZ	BLZ	NA	BZD	Dollar	501			en-BZ,es
24	\N	\N	Benin	BJ	BEN	AF	XOF	Franc	229			fr-BJ
25	\N	\N	Bermuda	BM	BMU	NA	BMD	Dollar	+1-441	@@ ##	^([A-Z]{2}d{2})$	en-BM,pt
26	\N	\N	Bhutan	BT	BTN	AS	BTN	Ngultrum	975			dz
27	\N	\N	Bolivia	BO	BOL	SA	BOB	Boliviano	591			es-BO,qu,ay
28	\N	\N	Bonaire, Saint Eustatius and Saba 	BQ	BES	NA	USD	Dollar	599			nl,pap,en
29	\N	\N	Bosnia and Herzegovina	BA	BIH	EU	BAM	Marka	387	#####	^(d{5})$	bs,hr-BA,sr-BA
30	\N	\N	Botswana	BW	BWA	AF	BWP	Pula	267			en-BW,tn-BW
31	\N	\N	Bouvet Island	BV	BVT	AN	NOK	Krone				
32	\N	\N	Brazil	BR	BRA	SA	BRL	Real	55	#####-###	^(d{8})$	pt-BR,es,en,fr
33	\N	\N	British Indian Ocean Territory	IO	IOT	AS	USD	Dollar	246			en-IO
34	\N	\N	British Virgin Islands	VG	VGB	NA	USD	Dollar	+1-284			en-VG
35	\N	\N	Brunei	BN	BRN	AS	BND	Dollar	673	@@####	^([A-Z]{2}d{4})$	ms-BN,en-BN
36	\N	\N	Bulgaria	BG	BGR	EU	BGN	Lev	359	####	^(d{4})$	bg,tr-BG
37	\N	\N	Burkina Faso	BF	BFA	AF	XOF	Franc	226			fr-BF
38	\N	\N	Burundi	BI	BDI	AF	BIF	Franc	257			fr-BI,rn
39	\N	\N	Cambodia	KH	KHM	AS	KHR	Riels	855	#####	^(d{5})$	km,fr,en
40	\N	\N	Cameroon	CM	CMR	AF	XAF	Franc	237			en-CM,fr-CM
41	\N	\N	Canada	CA	CAN	NA	CAD	Dollar	1	@#@ #@#	^([a-zA-Z]d[a-zA-Z]d	en-CA,fr-CA,iu
42	\N	\N	Cape Verde	CV	CPV	AF	CVE	Escudo	238	####	^(d{4})$	pt-CV
43	\N	\N	Cayman Islands	KY	CYM	NA	KYD	Dollar	+1-345			en-KY
44	\N	\N	Central African Republic	CF	CAF	AF	XAF	Franc	236			fr-CF,sg,ln,kg
45	\N	\N	Chad	TD	TCD	AF	XAF	Franc	235			fr-TD,ar-TD,sre
46	\N	\N	Chile	CL	CHL	SA	CLP	Peso	56	#######	^(d{7})$	es-CL
47	\N	\N	China	CN	CHN	AS	CNY	Yuan Renminbi	86	######	^(d{6})$	zh-CN,yue,wuu,dta,ug,za
48	\N	\N	Christmas Island	CX	CXR	AS	AUD	Dollar	61	####	^(d{4})$	en,zh,ms-CC
49	\N	\N	Cocos Islands	CC	CCK	AS	AUD	Dollar	61			ms-CC,en
50	\N	\N	Colombia	CO	COL	SA	COP	Peso	57			es-CO
51	\N	\N	Comoros	KM	COM	AF	KMF	Franc	269			ar,fr-KM
52	\N	\N	Cook Islands	CK	COK	OC	NZD	Dollar	682			en-CK,mi
53	\N	\N	Costa Rica	CR	CRI	NA	CRC	Colon	506	####	^(d{4})$	es-CR,en
54	\N	\N	Croatia	HR	HRV	EU	HRK	Kuna	385	HR-#####	^(?:HR)*(d{5})$	hr-HR,sr
55	\N	\N	Cuba	CU	CUB	NA	CUP	Peso	53	CP #####	^(?:CP)*(d{5})$	es-CU
56	\N	\N	Curacao	CW	CUW	NA	ANG	Guilder	599			nl,pap
57	\N	\N	Cyprus	CY	CYP	EU	EUR	Euro	357	####	^(d{4})$	el-CY,tr-CY,en
58	\N	\N	Czech Republic	CZ	CZE	EU	CZK	Koruna	420	### ##	^(d{5})$	cs,sk
59	\N	\N	Democratic Republic of the Congo	CD	COD	AF	CDF	Franc	243			fr-CD,ln,kg
60	\N	\N	Denmark	DK	DNK	EU	DKK	Krone	45	####	^(d{4})$	da-DK,en,fo,de-DK
61	\N	\N	Djibouti	DJ	DJI	AF	DJF	Franc	253			fr-DJ,ar,so-DJ,aa
62	\N	\N	Dominica	DM	DMA	NA	XCD	Dollar	+1-767			en-DM
63	\N	\N	Dominican Republic	DO	DOM	NA	DOP	Peso	+1-809 and	#####	^(d{5})$	es-DO
64	\N	\N	East Timor	TL	TLS	OC	USD	Dollar	670			tet,pt-TL,id,en
65	\N	\N	Ecuador	EC	ECU	SA	USD	Dollar	593	@####@	^([a-zA-Z]d{4}[a-zA-	es-EC
66	\N	\N	Egypt	EG	EGY	AF	EGP	Pound	20	#####	^(d{5})$	ar-EG,en,fr
67	\N	\N	El Salvador	SV	SLV	NA	USD	Dollar	503	CP ####	^(?:CP)*(d{4})$	es-SV
68	\N	\N	Equatorial Guinea	GQ	GNQ	AF	XAF	Franc	240			es-GQ,fr
69	\N	\N	Eritrea	ER	ERI	AF	ERN	Nakfa	291			aa-ER,ar,tig,kun,ti-ER
70	\N	\N	Estonia	EE	EST	EU	EUR	Euro	372	#####	^(d{5})$	et,ru
71	\N	\N	Ethiopia	ET	ETH	AF	ETB	Birr	251	####	^(d{4})$	am,en-ET,om-ET,ti-ET,so-ET,sid
72	\N	\N	Falkland Islands	FK	FLK	SA	FKP	Pound	500			en-FK
73	\N	\N	Faroe Islands	FO	FRO	EU	DKK	Krone	298	FO-###	^(?:FO)*(d{3})$	fo,da-FO
74	\N	\N	Fiji	FJ	FJI	OC	FJD	Dollar	679			en-FJ,fj
75	\N	\N	Finland	FI	FIN	EU	EUR	Euro	358	#####	^(?:FI)*(d{5})$	fi-FI,sv-FI,smn
76	\N	\N	France	FR	FRA	EU	EUR	Euro	33	#####	^(d{5})$	fr-FR,frp,br,co,ca,eu,oc
77	\N	\N	French Guiana	GF	GUF	SA	EUR	Euro	594	#####	^((97)|(98)3d{2})$	fr-GF
78	\N	\N	French Polynesia	PF	PYF	OC	XPF	Franc	689	#####	^((97)|(98)7d{2})$	fr-PF,ty
79	\N	\N	French Southern Territories	TF	ATF	AN	EUR	Euro				fr
80	\N	\N	Gabon	GA	GAB	AF	XAF	Franc	241			fr-GA
81	\N	\N	Gambia	GM	GMB	AF	GMD	Dalasi	220			en-GM,mnk,wof,wo,ff
82	\N	\N	Georgia	GE	GEO	AS	GEL	Lari	995	####	^(d{4})$	ka,ru,hy,az
83	\N	\N	Germany	DE	DEU	EU	EUR	Euro	49	#####	^(d{5})$	de
84	\N	\N	Ghana	GH	GHA	AF	GHS	Cedi	233			en-GH,ak,ee,tw
85	\N	\N	Gibraltar	GI	GIB	EU	GIP	Pound	350			en-GI,es,it,pt
86	\N	\N	Greece	GR	GRC	EU	EUR	Euro	30	### ##	^(d{5})$	el-GR,en,fr
87	\N	\N	Greenland	GL	GRL	NA	DKK	Krone	299	####	^(d{4})$	kl,da-GL,en
88	\N	\N	Grenada	GD	GRD	NA	XCD	Dollar	+1-473			en-GD
89	\N	\N	Guadeloupe	GP	GLP	NA	EUR	Euro	590	#####	^((97)|(98)d{3})$	fr-GP
90	\N	\N	Guam	GU	GUM	OC	USD	Dollar	+1-671	969##	^(969d{2})$	en-GU,ch-GU
91	\N	\N	Guatemala	GT	GTM	NA	GTQ	Quetzal	502	#####	^(d{5})$	es-GT
92	\N	\N	Guernsey	GG	GGY	EU	GBP	Pound	+44-1481	@# #@@|@## #@@|@@# #	^(([A-Z]d{2}[A-Z]{2}	en,fr
93	\N	\N	Guinea	GN	GIN	AF	GNF	Franc	224			fr-GN
94	\N	\N	Guinea-Bissau	GW	GNB	AF	XOF	Franc	245	####	^(d{4})$	pt-GW,pov
95	\N	\N	Guyana	GY	GUY	SA	GYD	Dollar	592			en-GY
96	\N	\N	Haiti	HT	HTI	NA	HTG	Gourde	509	HT####	^(?:HT)*(d{4})$	ht,fr-HT
97	\N	\N	Heard Island and McDonald Islands	HM	HMD	AN	AUD	Dollar				
98	\N	\N	Honduras	HN	HND	NA	HNL	Lempira	504	@@####	^([A-Z]{2}d{4})$	es-HN
99	\N	\N	Hong Kong	HK	HKG	AS	HKD	Dollar	852			zh-HK,yue,zh,en
100	\N	\N	Hungary	HU	HUN	EU	HUF	Forint	36	####	^(d{4})$	hu-HU
101	\N	\N	Iceland	IS	ISL	EU	ISK	Krona	354	###	^(d{3})$	is,en,de,da,sv,no
103	\N	\N	Indonesia	ID	IDN	AS	IDR	Rupiah	62	#####	^(d{5})$	id,en,nl,jv
104	\N	\N	Iran	IR	IRN	AS	IRR	Rial	98	##########	^(d{10})$	fa-IR,ku
105	\N	\N	Iraq	IQ	IRQ	AS	IQD	Dinar	964	#####	^(d{5})$	ar-IQ,ku,hy
106	\N	\N	Ireland	IE	IRL	EU	EUR	Euro	353			en-IE,ga-IE
143	\N	\N	Mexico	MX	MEX	NA	MXN	Peso	52	#####	^(d{5})$	es-MX
107	\N	\N	Isle of Man	IM	IMN	EU	GBP	Pound	+44-1624	@# #@@|@## #@@|@@# #	^(([A-Z]d{2}[A-Z]{2}	en,gv
108	\N	\N	Israel	IL	ISR	AS	ILS	Shekel	972	#####	^(d{5})$	he,ar-IL,en-IL,
109	\N	\N	Italy	IT	ITA	EU	EUR	Euro	39	#####	^(d{5})$	it-IT,de-IT,fr-IT,sc,ca,co,sl
110	\N	\N	Ivory Coast	CI	CIV	AF	XOF	Franc	225			fr-CI
111	\N	\N	Jamaica	JM	JAM	NA	JMD	Dollar	+1-876			en-JM
112	\N	\N	Japan	JP	JPN	AS	JPY	Yen	81	###-####	^(d{7})$	ja
113	\N	\N	Jersey	JE	JEY	EU	GBP	Pound	+44-1534	@# #@@|@## #@@|@@# #	^(([A-Z]d{2}[A-Z]{2}	en,pt
114	\N	\N	Jordan	JO	JOR	AS	JOD	Dinar	962	#####	^(d{5})$	ar-JO,en
115	\N	\N	Kazakhstan	KZ	KAZ	AS	KZT	Tenge	7	######	^(d{6})$	kk,ru
116	\N	\N	Kenya	KE	KEN	AF	KES	Shilling	254	#####	^(d{5})$	en-KE,sw-KE
117	\N	\N	Kiribati	KI	KIR	OC	AUD	Dollar	686			en-KI,gil
118	\N	\N	Kosovo	XK	XKX	EU	EUR	Euro				sq,sr
119	\N	\N	Kuwait	KW	KWT	AS	KWD	Dinar	965	#####	^(d{5})$	ar-KW,en
120	\N	\N	Kyrgyzstan	KG	KGZ	AS	KGS	Som	996	######	^(d{6})$	ky,uz,ru
121	\N	\N	Laos	LA	LAO	AS	LAK	Kip	856	#####	^(d{5})$	lo,fr,en
122	\N	\N	Latvia	LV	LVA	EU	LVL	Lat	371	LV-####	^(?:LV)*(d{4})$	lv,ru,lt
123	\N	\N	Lebanon	LB	LBN	AS	LBP	Pound	961	#### ####|####	^(d{4}(d{4})?)$	ar-LB,fr-LB,en,hy
124	\N	\N	Lesotho	LS	LSO	AF	LSL	Loti	266	###	^(d{3})$	en-LS,st,zu,xh
125	\N	\N	Liberia	LR	LBR	AF	LRD	Dollar	231	####	^(d{4})$	en-LR
126	\N	\N	Libya	LY	LBY	AF	LYD	Dinar	218			ar-LY,it,en
127	\N	\N	Liechtenstein	LI	LIE	EU	CHF	Franc	423	####	^(d{4})$	de-LI
128	\N	\N	Lithuania	LT	LTU	EU	LTL	Litas	370	LT-#####	^(?:LT)*(d{5})$	lt,ru,pl
129	\N	\N	Luxembourg	LU	LUX	EU	EUR	Euro	352	####	^(d{4})$	lb,de-LU,fr-LU
130	\N	\N	Macao	MO	MAC	AS	MOP	Pataca	853			zh,zh-MO,pt
131	\N	\N	Macedonia	MK	MKD	EU	MKD	Denar	389	####	^(d{4})$	mk,sq,tr,rmm,sr
132	\N	\N	Madagascar	MG	MDG	AF	MGA	Ariary	261	###	^(d{3})$	fr-MG,mg
133	\N	\N	Malawi	MW	MWI	AF	MWK	Kwacha	265			ny,yao,tum,swk
134	\N	\N	Malaysia	MY	MYS	AS	MYR	Ringgit	60	#####	^(d{5})$	ms-MY,en,zh,ta,te,ml,pa,th
135	\N	\N	Maldives	MV	MDV	AS	MVR	Rufiyaa	960	#####	^(d{5})$	dv,en
136	\N	\N	Mali	ML	MLI	AF	XOF	Franc	223			fr-ML,bm
137	\N	\N	Malta	MT	MLT	EU	EUR	Euro	356	@@@ ###|@@@ ##	^([A-Z]{3}d{2}d?)$	mt,en-MT
138	\N	\N	Marshall Islands	MH	MHL	OC	USD	Dollar	692			mh,en-MH
139	\N	\N	Martinique	MQ	MTQ	NA	EUR	Euro	596	#####	^(d{5})$	fr-MQ
140	\N	\N	Mauritania	MR	MRT	AF	MRO	Ouguiya	222			ar-MR,fuc,snk,fr,mey,wo
141	\N	\N	Mauritius	MU	MUS	AF	MUR	Rupee	230			en-MU,bho,fr
142	\N	\N	Mayotte	YT	MYT	AF	EUR	Euro	262	#####	^(d{5})$	fr-YT
144	\N	\N	Micronesia	FM	FSM	OC	USD	Dollar	691	#####	^(d{5})$	en-FM,chk,pon,yap,kos,uli,woe,nkr,kpg
145	\N	\N	Moldova	MD	MDA	EU	MDL	Leu	373	MD-####	^(?:MD)*(d{4})$	ro,ru,gag,tr
146	\N	\N	Monaco	MC	MCO	EU	EUR	Euro	377	#####	^(d{5})$	fr-MC,en,it
147	\N	\N	Mongolia	MN	MNG	AS	MNT	Tugrik	976	######	^(d{6})$	mn,ru
148	\N	\N	Montenegro	ME	MNE	EU	EUR	Euro	382	#####	^(d{5})$	sr,hu,bs,sq,hr,rom
149	\N	\N	Montserrat	MS	MSR	NA	XCD	Dollar	+1-664			en-MS
150	\N	\N	Morocco	MA	MAR	AF	MAD	Dirham	212	#####	^(d{5})$	ar-MA,fr
151	\N	\N	Mozambique	MZ	MOZ	AF	MZN	Metical	258	####	^(d{4})$	pt-MZ,vmw
152	\N	\N	Myanmar	MM	MMR	AS	MMK	Kyat	95	#####	^(d{5})$	my
153	\N	\N	Namibia	NA	NAM	AF	NAD	Dollar	264			en-NA,af,de,hz,naq
154	\N	\N	Nauru	NR	NRU	OC	AUD	Dollar	674			na,en-NR
155	\N	\N	Nepal	NP	NPL	AS	NPR	Rupee	977	#####	^(d{5})$	ne,en
156	\N	\N	Netherlands	NL	NLD	EU	EUR	Euro	31	#### @@	^(d{4}[A-Z]{2})$	nl-NL,fy-NL
157	\N	\N	Netherlands Antilles	AN	ANT	NA	ANG	Guilder	599			nl-AN,en,es
158	\N	\N	New Caledonia	NC	NCL	OC	XPF	Franc	687	#####	^(d{5})$	fr-NC
159	\N	\N	New Zealand	NZ	NZL	OC	NZD	Dollar	64	####	^(d{4})$	en-NZ,mi
160	\N	\N	Nicaragua	NI	NIC	NA	NIO	Cordoba	505	###-###-#	^(d{7})$	es-NI,en
161	\N	\N	Niger	NE	NER	AF	XOF	Franc	227	####	^(d{4})$	fr-NE,ha,kr,dje
162	\N	\N	Nigeria	NG	NGA	AF	NGN	Naira	234	######	^(d{6})$	en-NG,ha,yo,ig,ff
163	\N	\N	Niue	NU	NIU	OC	NZD	Dollar	683			niu,en-NU
164	\N	\N	Norfolk Island	NF	NFK	OC	AUD	Dollar	672			en-NF
165	\N	\N	North Korea	KP	PRK	AS	KPW	Won	850	###-###	^(d{6})$	ko-KP
166	\N	\N	Northern Mariana Islands	MP	MNP	OC	USD	Dollar	+1-670			fil,tl,zh,ch-MP,en-MP
167	\N	\N	Norway	NO	NOR	EU	NOK	Krone	47	####	^(d{4})$	no,nb,nn,se,fi
168	\N	\N	Oman	OM	OMN	AS	OMR	Rial	968	###	^(d{3})$	ar-OM,en,bal,ur
169	\N	\N	Pakistan	PK	PAK	AS	PKR	Rupee	92	#####	^(d{5})$	ur-PK,en-PK,pa,sd,ps,brh
170	\N	\N	Palau	PW	PLW	OC	USD	Dollar	680	96940	^(96940)$	pau,sov,en-PW,tox,ja,fil,zh
171	\N	\N	Palestinian Territory	PS	PSE	AS	ILS	Shekel	970			ar-PS
172	\N	\N	Panama	PA	PAN	NA	PAB	Balboa	507			es-PA,en
173	\N	\N	Papua New Guinea	PG	PNG	OC	PGK	Kina	675	###	^(d{3})$	en-PG,ho,meu,tpi
174	\N	\N	Paraguay	PY	PRY	SA	PYG	Guarani	595	####	^(d{4})$	es-PY,gn
175	\N	\N	Peru	PE	PER	SA	PEN	Sol	51			es-PE,qu,ay
176	\N	\N	Philippines	PH	PHL	AS	PHP	Peso	63	####	^(d{4})$	tl,en-PH,fil
177	\N	\N	Pitcairn	PN	PCN	OC	NZD	Dollar	870			en-PN
178	\N	\N	Poland	PL	POL	EU	PLN	Zloty	48	##-###	^(d{5})$	pl
179	\N	\N	Portugal	PT	PRT	EU	EUR	Euro	351	####-###	^(d{7})$	pt-PT,mwl
180	\N	\N	Puerto Rico	PR	PRI	NA	USD	Dollar	+1-787 and	#####-####	^(d{9})$	en-PR,es-PR
181	\N	\N	Qatar	QA	QAT	AS	QAR	Rial	974			ar-QA,es
182	\N	\N	Republic of the Congo	CG	COG	AF	XAF	Franc	242			fr-CG,kg,ln-CG
183	\N	\N	Reunion	RE	REU	AF	EUR	Euro	262	#####	^((97)|(98)(4|7|8)d{	fr-RE
184	\N	\N	Romania	RO	ROU	EU	RON	Leu	40	######	^(d{6})$	ro,hu,rom
185	\N	\N	Russia	RU	RUS	EU	RUB	Ruble	7	######	^(d{6})$	ru,tt,xal,cau,ady,kv,ce,tyv,cv,udm,tut,mns,bua,myv,mdf,chm,ba,inh,tut,kbd,krc,ava,sah,nog
186	\N	\N	Rwanda	RW	RWA	AF	RWF	Franc	250			rw,en-RW,fr-RW,sw
187	\N	\N	Saint Barthelemy	BL	BLM	NA	EUR	Euro	590	### ###		fr
188	\N	\N	Saint Helena	SH	SHN	AF	SHP	Pound	290	STHL 1ZZ	^(STHL1ZZ)$	en-SH
189	\N	\N	Saint Kitts and Nevis	KN	KNA	NA	XCD	Dollar	+1-869			en-KN
190	\N	\N	Saint Lucia	LC	LCA	NA	XCD	Dollar	+1-758			en-LC
191	\N	\N	Saint Martin	MF	MAF	NA	EUR	Euro	590	### ###		fr
192	\N	\N	Saint Pierre and Miquelon	PM	SPM	NA	EUR	Euro	508	#####	^(97500)$	fr-PM
193	\N	\N	Saint Vincent and the Grenadines	VC	VCT	NA	XCD	Dollar	+1-784			en-VC,fr
194	\N	\N	Samoa	WS	WSM	OC	WST	Tala	685			sm,en-WS
195	\N	\N	San Marino	SM	SMR	EU	EUR	Euro	378	4789#	^(4789d)$	it-SM
196	\N	\N	Sao Tome and Principe	ST	STP	AF	STD	Dobra	239			pt-ST
197	\N	\N	Saudi Arabia	SA	SAU	AS	SAR	Rial	966	#####	^(d{5})$	ar-SA
198	\N	\N	Senegal	SN	SEN	AF	XOF	Franc	221	#####	^(d{5})$	fr-SN,wo,fuc,mnk
199	\N	\N	Serbia	RS	SRB	EU	RSD	Dinar	381	######	^(d{6})$	sr,hu,bs,rom
200	\N	\N	Serbia and Montenegro	CS	SCG	EU	RSD	Dinar	381	#####	^(d{5})$	cu,hu,sq,sr
201	\N	\N	Seychelles	SC	SYC	AF	SCR	Rupee	248			en-SC,fr-SC
202	\N	\N	Sierra Leone	SL	SLE	AF	SLL	Leone	232			en-SL,men,tem
203	\N	\N	Singapore	SG	SGP	AS	SGD	Dollar	65	######	^(d{6})$	cmn,en-SG,ms-SG,ta-SG,zh-SG
204	\N	\N	Sint Maarten	SX	SXM	NA	ANG	Guilder	599			nl,en
205	\N	\N	Slovakia	SK	SVK	EU	EUR	Euro	421	###  ##	^(d{5})$	sk,hu
206	\N	\N	Slovenia	SI	SVN	EU	EUR	Euro	386	SI- ####	^(?:SI)*(d{4})$	sl,sh
207	\N	\N	Solomon Islands	SB	SLB	OC	SBD	Dollar	677			en-SB,tpi
208	\N	\N	Somalia	SO	SOM	AF	SOS	Shilling	252	@@  #####	^([A-Z]{2}d{5})$	so-SO,ar-SO,it,en-SO
209	\N	\N	South Africa	ZA	ZAF	AF	ZAR	Rand	27	####	^(d{4})$	zu,xh,af,nso,en-ZA,tn,st,ts,ss,ve,nr
210	\N	\N	South Georgia and the South Sandwich Islands	GS	SGS	AN	GBP	Pound				en
211	\N	\N	South Korea	KR	KOR	AS	KRW	Won	82	SEOUL ###-###	^(?:SEOUL)*(d{6})$	ko-KR,en
212	\N	\N	South Sudan	SS	SSD	AF	SSP	Pound	211			en
213	\N	\N	Spain	ES	ESP	EU	EUR	Euro	34	#####	^(d{5})$	es-ES,ca,gl,eu,oc
214	\N	\N	Sri Lanka	LK	LKA	AS	LKR	Rupee	94	#####	^(d{5})$	si,ta,en
215	\N	\N	Sudan	SD	SDN	AF	SDG	Pound	249	#####	^(d{5})$	ar-SD,en,fia
216	\N	\N	Suriname	SR	SUR	SA	SRD	Dollar	597			nl-SR,en,srn,hns,jv
217	\N	\N	Svalbard and Jan Mayen	SJ	SJM	EU	NOK	Krone	47			no,ru
218	\N	\N	Swaziland	SZ	SWZ	AF	SZL	Lilangeni	268	@###	^([A-Z]d{3})$	en-SZ,ss-SZ
219	\N	\N	Sweden	SE	SWE	EU	SEK	Krona	46	SE-### ##	^(?:SE)*(d{5})$	sv-SE,se,sma,fi-SE
220	\N	\N	Switzerland	CH	CHE	EU	CHF	Franc	41	####	^(d{4})$	de-CH,fr-CH,it-CH,rm
221	\N	\N	Syria	SY	SYR	AS	SYP	Pound	963			ar-SY,ku,hy,arc,fr,en
222	\N	\N	Taiwan	TW	TWN	AS	TWD	Dollar	886	#####	^(d{5})$	zh-TW,zh,nan,hak
223	\N	\N	Tajikistan	TJ	TJK	AS	TJS	Somoni	992	######	^(d{6})$	tg,ru
224	\N	\N	Tanzania	TZ	TZA	AF	TZS	Shilling	255			sw-TZ,en,ar
225	\N	\N	Thailand	TH	THA	AS	THB	Baht	66	#####	^(d{5})$	th,en
226	\N	\N	Togo	TG	TGO	AF	XOF	Franc	228			fr-TG,ee,hna,kbp,dag,ha
227	\N	\N	Tokelau	TK	TKL	OC	NZD	Dollar	690			tkl,en-TK
228	\N	\N	Tonga	TO	TON	OC	TOP	Paanga	676			to,en-TO
229	\N	\N	Trinidad and Tobago	TT	TTO	NA	TTD	Dollar	+1-868			en-TT,hns,fr,es,zh
230	\N	\N	Tunisia	TN	TUN	AF	TND	Dinar	216	####	^(d{4})$	ar-TN,fr
231	\N	\N	Turkey	TR	TUR	AS	TRY	Lira	90	#####	^(d{5})$	tr-TR,ku,diq,az,av
232	\N	\N	Turkmenistan	TM	TKM	AS	TMT	Manat	993	######	^(d{6})$	tk,ru,uz
233	\N	\N	Turks and Caicos Islands	TC	TCA	NA	USD	Dollar	+1-649	TKCA 1ZZ	^(TKCA 1ZZ)$	en-TC
234	\N	\N	Tuvalu	TV	TUV	OC	AUD	Dollar	688			tvl,en,sm,gil
235	\N	\N	U.S. Virgin Islands	VI	VIR	NA	USD	Dollar	+1-340			en-VI
236	\N	\N	Uganda	UG	UGA	AF	UGX	Shilling	256			en-UG,lg,sw,ar
237	\N	\N	Ukraine	UA	UKR	EU	UAH	Hryvnia	380	#####	^(d{5})$	uk,ru-UA,rom,pl,hu
238	\N	\N	United Arab Emirates	AE	ARE	AS	AED	Dirham	971			ar-AE,fa,en,hi,ur
239	\N	\N	United Kingdom	GB	GBR	EU	GBP	Pound	44	@# #@@|@## #@@|@@# #	^(([A-Z]d{2}[A-Z]{2}	en-GB,cy-GB,gd
240	\N	\N	United States	US	USA	NA	USD	Dollar	1	#####-####	^(d{9})$	en-US,es-US,haw,fr
241	\N	\N	United States Minor Outlying Islands	UM	UMI	OC	USD	Dollar	1			en-UM
242	\N	\N	Uruguay	UY	URY	SA	UYU	Peso	598	#####	^(d{5})$	es-UY
243	\N	\N	Uzbekistan	UZ	UZB	AS	UZS	Som	998	######	^(d{6})$	uz,ru,tg
244	\N	\N	Vanuatu	VU	VUT	OC	VUV	Vatu	678			bi,en-VU,fr-VU
245	\N	\N	Vatican	VA	VAT	EU	EUR	Euro	379			la,it,fr
246	\N	\N	Venezuela	VE	VEN	SA	VEF	Bolivar	58	####	^(d{4})$	es-VE
247	\N	\N	Vietnam	VN	VNM	AS	VND	Dong	84	######	^(d{6})$	vi,en,fr,zh,km
248	\N	\N	Wallis and Futuna	WF	WLF	OC	XPF	Franc	681	#####	^(986d{2})$	wls,fud,fr-WF
249	\N	\N	Western Sahara	EH	ESH	AF	MAD	Dirham	212			ar,mey
250	\N	\N	Yemen	YE	YEM	AS	YER	Rial	967			ar-YE
251	\N	\N	Zambia	ZM	ZMB	AF	ZMK	Kwacha	260	#####	^(d{5})$	en-ZM,bem,loz,lun,lue,ny,toi
252	\N	\N	Zimbabwe	ZW	ZWE	AF	ZWL	Dollar	263			en-ZW,sn,nr,nd
102	\N	\N	India	IN	IND	AS	INR	Rupee	+91	######	^(d{6})$	en-IN,hi,bn,te,mr,ta,ur,gu,kn,ml,or,pa,as,bh,sat,ks,ne,sd,kok,doi,mni,sit,sa,fr,lus,inc
\.


--
-- Name: countries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('countries_id_seq', 253, false);


--
-- Data for Name: coupons; Type: TABLE DATA; Schema: public; Owner: -
--

COPY coupons (id, created_at, updated_at, user_id, restaurant_id, coupon_code, discount, is_flat_discount_in_amount, no_of_quantity_allowed, no_of_quantity_used, validity_start_date, validity_end_date, maximum_discount_amount, is_active) FROM stdin;
\.


--
-- Name: coupons_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('coupons_id_seq', 1, false);


--
-- Data for Name: cuisines; Type: TABLE DATA; Schema: public; Owner: -
--

COPY cuisines (id, created_at, updated_at, name, is_active, slug) FROM stdin;
\.


--
-- Name: cuisines_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('cuisines_id_seq', 1, true);


--
-- Data for Name: device_details; Type: TABLE DATA; Schema: public; Owner: -
--

COPY device_details (id, created_at, updated_at, user_id, appname, appversion, deviceuid, devicetoken, devicename, devicemodel, deviceversion, pushbadge, pushalert, pushsound, development, status, latitude, longitude, devicetype) FROM stdin;
\.


--
-- Name: device_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('device_details_id_seq', 1, false);


--
-- Data for Name: email_templates; Type: TABLE DATA; Schema: public; Owner: -
--

COPY email_templates (id, created_at, updated_at, name, description, from_email, reply_to_email, subject, email_variables, html_email_content, text_email_content, display_name, to_email, is_admin_email, plugin, is_html) FROM stdin;
1	2016-05-30 11:13:01	2016-05-30 11:13:01	welcomemail	we will send this mail, when user register in this site and get activate.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Welcome to ##SITE_NAME##	SITE_NAME, SITE_URL,USERNAME, SUPPORT_EMAIL,SITE_URL	Hi ##USERNAME##,\r\n\r\n  We wish to say a quick hello and thanks for registering at ##SITE_NAME##.\r\n  \r\n  If you did not request this account and feel this is in error, please contact us at ##SUPPORT_EMAIL##\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\n  We wish to say a quick hello and thanks for registering at ##SITE_NAME##.\r\n  \r\n  If you did not request this account and feel this is in error, please contact us at ##SUPPORT_EMAIL##\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##	Welcome Mail	##TO_EMAIL##	f	\N	f
3	2016-05-30 11:23:46	2016-05-30 11:23:46	changepassword	we will send this mail\r\nto user, when the user change password.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Password changed	SITE_NAME,SITE_URL,PASSWORD,USERNAME	Hi ##USERNAME##,\r\n\r\nYour password has been changed\r\n\r\nYour new password:\r\n##PASSWORD##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nYour password has been changed\r\n\r\nYour new password:\r\n##PASSWORD##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Change Password	##TO_EMAIL##	f	\N	f
2	2016-05-30 11:21:09	2016-05-30 11:21:09	activationrequest	we will send this mail,\r\nwhen user registering an account he/she will get an activation\r\nrequest.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Please activate your ##SITE_NAME## account	SITE_NAME,SITE_URL,USERNAME,ACTIVATION_URL	Hi ##USERNAME##,\r\n\r\nYour account has been created. Please visit the following URL to activate your account.\r\n##ACTIVATION_URL##\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nYour account has been created. Please visit the following URL to activate your account.\r\n##ACTIVATION_URL##\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##	Activation Request	##TO_EMAIL##	f	\N	f
5	2016-05-30 11:29:19	2016-05-30 11:29:19	adminuseredit	we will send this mail\r\ninto user, when admin edit users profile.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	[##SITE_NAME##] Profile updated	SITE_NAME,EMAIL,USERNAME	Hi ##USERNAME##,\r\n\r\nAdmin updated your profile in ##SITE_NAME## account.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nAdmin updated your profile in ##SITE_NAME## account.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Admin User Edit	##TO_EMAIL##	f	\N	f
17	2016-05-30 12:24:36	2016-05-30 12:24:36	adminuserdelete	We will send this mail to user, when user delete by administrator.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Your ##SITE_NAME## account has been removed	SITE_NAME,USERNAME, SITE_URL	Dear ##USERNAME##,\r\n\r\nYour ##SITE_NAME## account has been removed.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Dear ##USERNAME##,\r\n\r\nYour ##SITE_NAME## account has been removed.\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Admin User Delete	##TO_EMAIL##	f	\N	f
10	2016-05-30 12:03:42	2016-05-30 12:03:42	orderrejected	We will send mail to user once restaurant rejected order.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Order Rejected	SITE_NAME,SITE_URL,USERANME,RESTAURANT_NAME,ORDERURL,SITE_URL	Hi ##USERNAME##,\r\n\r\n RESTAURANT_NAME has been rejected in your order request. Please find other restaurants.\r\n  \r\nOrder Details:\r\nORDERURL\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##'	Hi ##USERNAME##,\r\n\r\n RESTAURANT_NAME has been rejected in your order request. Please find other restaurants.\r\n  \r\nOrder Details:\r\nORDERURL\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##'	Order Rejected	##TO_EMAIL##	f	Order/Order	f
11	2016-05-30 11:34:35	2016-05-30 11:34:35	supervisorwelcomemail	we will\r\nsend this mail to supervisor base user, when a new supervisor added by restaurant in the site.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Welcome to  ##SITE_NAME##	SITE_NAME,RESTAURANT_NAME,SITE_URL,USERNAME,PASSWORD	Hi,\r\n\r\n  We will welcome to the ##RESTAURANT_NAME## restaurant as a supervisor.  \r\n\r\nAccount details:\r\n\r\n##SITE_URL##\r\n\r\nUsername :  ##USERNAME##\r\nPassword :  ##PASSWORD##  \r\n \r\nThanks,\r\n\r\n##SITE_NAME##\r\n	Hi,\r\n\r\n  We will welcome to the ##RESTAURANT_NAME## restaurant as a supervisor.  \r\n\r\nAccount details:\r\n\r\n##SITE_URL##\r\n\r\nUsername :  ##USERNAME##\r\nPassword :  ##PASSWORD##  \r\n \r\nThanks,\r\n\r\n##SITE_NAME##\r\n	Supervisor Welcome Mail	##TO_EMAIL##	f	Order/Supervisor	f
12	2016-05-30 11:34:35	2016-05-30 11:34:35	deliverypersonwelcomemail	we will\r\nsend this mail to delivery person base user, when a new delivery person added by restaurant / Supervisor in the site.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Welcome to  ##SITE_NAME##	SITE_NAME,RESTAURANT_NAME,SITE_URL,USERNAME,PASSWORD	Hi,\r\n\r\n  We will welcome to the ##RESTAURANT_NAME## restaurant as a delivery person.  \r\n\r\nAccount details:\r\n\r\n##SITE_URL##\r\n\r\nUsername :  ##USERNAME##\r\nPassword :  ##PASSWORD##  \r\n \r\nThanks,\r\n\r\n##SITE_NAME##\r\n	Hi,\r\n\r\n  We will welcome to the ##RESTAURANT_NAME## restaurant as a delivery person.  \r\n\r\nAccount details:\r\n\r\n##SITE_URL##\r\n\r\nUsername :  ##USERNAME##\r\nPassword :  ##PASSWORD##  \r\n \r\nThanks,\r\n\r\n##SITE_NAME##\r\n	Delivery Person Welcome Mail	##TO_EMAIL##	f	Order/Delivery	f
9	2016-05-30 11:59:48	2016-05-30 11:59:48	adminpaidyourwithdrawalrequest	We will send mail to restaurant once the admin paid.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Amount paid	SITE_NAME,RESTAURANT_NAME,SITE_URL,WITHDRAWAL_URL	Hi ##RESTAURANT_NAME##,\r\n\r\n  We have paid your amount as you have requested from withdrawal requested.\r\n\r\nWithdrawal:\r\n\r\n##WITHDRAWAL_URL##\r\n\r\n  \r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##'	Hi ##RESTAURANT_NAME##,\r\n\r\n  We have paid your amount as you have requested from withdrawal requested.\r\n\r\nWithdrawal:\r\n\r\n##WITHDRAWAL_URL##\r\n\r\n  \r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##'	Paid Withdrawal Request	##TO_EMAIL##	f	Common/Withdrawal	f
13	2016-05-30 12:24:36	2016-05-30 12:24:36	newuserjoin	we will send this mail to admin, when a new user registered in the site. For this you have to enable "admin mail after register" in the settings page.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	[##SITE_NAME##] New user joined	SITE_NAME,USERNAME, SITE_URL,USEREMAIL	Hi,\r\n\r\nA new user named "##USERNAME##" has joined in ##SITE_NAME##.\r\n\r\nUsername: ##USERNAME##\r\nEmail: ##USEREMAIL##\r\n\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi,\r\n\r\nA new user named "##USERNAME##" has joined in ##SITE_NAME##.\r\n\r\nUsername: ##USERNAME##\r\nEmail: ##USEREMAIL##\r\n\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	New User Join	##TO_EMAIL##	f	\N	f
20	2016-05-30 12:24:36	2016-05-30 12:24:36	contactusreplymail	we will send this mail ti user, when user submit the contact us form.	##SITE_CONTACT_EMAIL##		RE: ##SUBJECT##	MESSAGE, POST_DATE, SITE_NAME, CONTACT_URL, FIRST_NAME, LAST_NAME, SUBJECT, SITE_URL	Dear ##FIRST_NAME####LAST_NAME##,\r\n\r\nThanks for contacting us. We'll get back to you shortly.\r\n\r\nPlease do not reply to this automated response. If you have not contacted us and if you feel this is an error, please contact us through our site ##CONTACT_URL##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##\r\n\r\n------ On ##POST_DATE## you wrote from ##IP## -----\r\n\r\n##MESSAGE##\r\n	Dear ##FIRST_NAME####LAST_NAME##,\r\n\r\nThanks for contacting us. We'll get back to you shortly.\r\n\r\nPlease do not reply to this automated response. If you have not contacted us and if you feel this is an error, please contact us through our site ##CONTACT_URL##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##\r\n\r\n------ On ##POST_DATE## you wrote from ##IP## -----\r\n\r\n##MESSAGE##\r\n	Contact Us Auto Reply	##TO_EMAIL##	f	\N	f
18	2016-05-30 12:24:36	2016-05-30 12:24:36	adminchangepassword	we will send this mail to user, when admin change users password.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Password changed	SITE_NAME,PASSWORD,USERNAME, SITE_URL	Hi ##USERNAME##,\r\n\r\nAdmin reset your password for your  ##SITE_NAME## account.\r\n\r\nYour new password: ##PASSWORD##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Hi ##USERNAME##,\r\n\r\nAdmin reset your password for your  ##SITE_NAME## account.\r\n\r\nYour new password: ##PASSWORD##\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Admin Change Password	##TO_EMAIL##	f	\N	f
22	2016-05-30 11:27:24	2016-05-30 11:27:24	failedsocialuser	we will send this mail, when user submit the forgot password form and the user users social network websites like twitter, facebook to register.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Forgot password request failed	SITE_NAME, SITE_URL,USEREMAIL	Hi ##USERNAME##, \r\n\r\nYour forgot password request was failed because you have registered via ##OTHER_SITE## site.\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##	Hi ##USERNAME##, \r\n\r\nYour forgot password request was failed because you have registered via ##OTHER_SITE## site.\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##	Failed Social User	##TO_EMAIL##	f	\N	f
19	2016-05-30 12:24:36	2016-05-30 12:24:36	contactus	We will send this mail to admin, when user submit any contact form.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	[##SITE_NAME##] ##SUBJECT##	FIRST_NAME ,LAST_NAME,FROM_EMAIL,IP,TELEPHONE, MESSAGE, SUBJECT,SITE_NAME,SITE_URL	##MESSAGE##\r\n\r\n----------------------------------------------------\r\nFirst Name   : ##FIRST_NAME##  \r\nLast Name    : ##LAST_NAME## \r\nEmail        : ##FROM_EMAIL##\r\nTelephone    : ##TELEPHONE##\r\nIP           : ##IP##\r\nWhois        : http://whois.sc/##IP##\r\n\r\n----------------------------------------------------\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	##MESSAGE##\r\n\r\n----------------------------------------------------\r\nFirst Name   : ##FIRST_NAME##  \r\nLast Name    : ##LAST_NAME## \r\nEmail        : ##FROM_EMAIL##\r\nTelephone    : ##TELEPHONE##\r\nIP           : ##IP##\r\nWhois        : http://whois.sc/##IP##\r\n\r\n----------------------------------------------------\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Contact Us	##TO_EMAIL##	f	\N	f
14	2016-05-30 12:24:36	2016-05-30 12:24:36	adminuseradd	we will send this mail to user, when a admin add a new user.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Welcome to ##SITE_NAME##	SITE_NAME, USERNAME, PASSWORD, LOGINLABEL, USEDTOLOGIN, SITE_URL	Dear ##USERNAME##,\r\n\r\n##SITE_NAME## team added you as a user in ##SITE_NAME##.\r\n\r\nYour account details.\r\n##LOGINLABEL##:##USEDTOLOGIN##\r\nPassword:##PASSWORD##\r\n\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Dear ##USERNAME##,\r\n\r\n##SITE_NAME## team added you as a user in ##SITE_NAME##.\r\n\r\nYour account details.\r\n##LOGINLABEL##:##USEDTOLOGIN##\r\nPassword:##PASSWORD##\r\n\r\n\r\nThanks,\r\n##SITE_NAME##\r\n##SITE_URL##	Admin User Add	##TO_EMAIL##	f	\N	f
21	2016-05-30 11:27:24	2016-05-30 11:27:24	failledforgotpassword	we will send this mail, when user submit the forgot password form.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Failed Forgot Password	SITE_NAME, SITE_URL,USEREMAIL	Hi there,\r\n\r\nYou (or someone else) entered this email address when trying to change the password of an ##USEREMAIL## account.\r\n\r\nHowever, this email address is not in our registered users and therefore the attempted password request has failed. If you are our customer and were expecting this email, please try again using the email you gave when opening your account.\r\n\r\nIf you are not an ##SITE_NAME## customer, please ignore this email. If you did not request this action and feel this is an error, please contact us ##SUPPORT_EMAIL##.\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##	Hi there,\r\n\r\nYou (or someone else) entered this email address when trying to change the password of an ##USEREMAIL## account.\r\n\r\nHowever, this email address is not in our registered users and therefore the attempted password request has failed. If you are our customer and were expecting this email, please try again using the email you gave when opening your account.\r\n\r\nIf you are not an ##SITE_NAME## customer, please ignore this email. If you did not request this action and feel this is an error, please contact us ##SUPPORT_EMAIL##.\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##	Failed Forgot Password	##TO_EMAIL##	f	\N	f
4	2016-05-30 11:27:24	2016-05-30 11:27:24	forgotpassword	we will send this mail, when\r\nuser submit the forgot password form.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Forgot password	USERNAME,PASSWORD,SITE_NAME,SITE_URL	Hi ##USERNAME##, \r\n\r\nWe have changed new password as per your requested.\r\n\r\nNew password: \r\n\r\n##PASSWORD##\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##	Hi ##USERNAME##, \r\n\r\nWe have changed new password as per your requested.\r\n\r\nNew password: \r\n\r\n##PASSWORD##\r\n\r\nThanks, \r\n##SITE_NAME## \r\n##SITE_URL##	Forgot Password	##TO_EMAIL##	f	\N	f
24	2016-05-30 12:03:42	2016-05-30 12:03:42	ordercancelled	We will send mail to user once user canceled order.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Order Cancelled	SITE_NAME,SITE_URL,USERANME,RESTAURANT_NAME,ORDERURL,SITE_URL	Hi ##USERNAME##,\n\n Your Order has been cancelled. \n \nOrder Details:\nRestaurant Name : \t##RESTAURANT_NAME## \nAmount \t\t\t:\t##AMOUNT##\nDate\t\t\t:\t##DATE##\n\nThanks,\n\n##SITE_NAME##\n##SITE_URL##	Hi ##USERNAME##,\n\n Your Order has been cancelled. \n \nOrder Details:\nRestaurant Name : \t##RESTAURANT_NAME## \nAmount \t\t\t:\t##AMOUNT##\nDate\t\t\t:\t##DATE##\n\nThanks,\n\n##SITE_NAME##\n##SITE_URL##	Order Cancelled	##TO_EMAIL##	f	\N	f
6	2016-05-30 11:34:35	2016-05-30 11:34:35	restaurantwelcomemail	we will\r\nsend this mail to restaurant base user, when a new restaurant registered in the site.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Welcome to ##SITE_NAME## 	SITE_NAME,RESTAURANT_NAME,SITE_URL,USERNAME,PASSWORD	Hi,\r\n\r\n  We wish to say a quick hello and thanks for registered in your ##RESTAURANT_NAME## restaurant in the ##SITE_NAME##.\r\n\r\nAccount details:\r\n\r\n##SITE_URL##\r\n\r\nUsername :  ##USERNAME##\r\nPassword :  ##PASSWORD##  \r\n \r\nThanks,\r\n\r\n##SITE_NAME##\r\n	Hi,\r\n\r\n  We wish to say a quick hello and thanks for registered in your ##RESTAURANT_NAME## restaurant in the ##SITE_NAME##.\r\n\r\nAccount details:\r\n\r\n##SITE_URL##\r\n\r\nUsername :  ##USERNAME##\r\nPassword :  ##PASSWORD##  \r\n \r\nThanks,\r\n\r\n##SITE_NAME##\r\n	Restaurant Welcome Mail	##TO_EMAIL##	f	Restaurant/MultiRestaurant	f
7	2016-05-30 11:48:09	2016-05-30 11:48:09	ordermailtorestaurant	We will send mail to restaurant once user placed order.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	[##SITE_NAME##] New order received [##ORDERID##]	SITE_NAME,RESTAURANT_NAME,ORDERID,ORDERURL,SITE_URL	Hi ##RESTAURANT_NAME##,\r\n\r\n  New order has been received form the user in ##SITE_NAME##.\r\n  \r\nOrder Details:\r\n##ORDERURL##\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##'	Hi ##RESTAURANT_NAME##,\r\n\r\n  New order has been received form the user in ##SITE_NAME##.\r\n  \r\nOrder Details:\r\n##ORDERURL##\r\n\r\nThanks,\r\n\r\n##SITE_NAME##\r\n##SITE_URL##'	Order Mail to Restaurant	##TO_EMAIL##	f	Order/Order	f
25	2017-12-18 13:05:27	2017-12-18 13:05:27	orderDelivered	We will send mail to user once restaurant delivered order.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Order Delivered	SITE_NAME,USERNAME,ORDER_URL,RESTAURANT_NAME,ORDER_NO	Hi ##USERNAME##,\n\n ##RESTAURANT_NAME## has been delivered your order ##ORDER_NO##. We hope you enjoyed the ##SITE_NAME## Assured Experience.\n  \nOrder Details:\n##ORDER_URL##\n\nThanks,\n\n##SITE_NAME##\n##SITE_URL##	Hi ##USERNAME##,\n\n ##RESTAURANT_NAME## has been delivered your order ##ORDER_NO##. We hope you enjoyed the ##SITE_NAME## Assured Experience.\n  \nOrder Details:\n##ORDER_URL##\n\nThanks,\n\n##SITE_NAME##\n##SITE_URL##	Order Delivered	##TO_EMAIL##	f	Order/Order	f
26	2017-12-18 13:29:31	2017-12-18 13:29:31	deliveryPersonAssigned	We will send this mail to user once restaurant assigned deliveryperson.	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Delivery Person Assigned	SITE_NAME,USERNAME,ORDER_URL,ORDER_URL,SITE_URL,AMOUNT,CURRENCY_SYMBOL	Hi ##USERNAME##,\n\n your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## will be delivered shortly. Thanks for using ##SITE_NAME##.\n  \nOrder Details:\n##ORDER_URL##\n\nThanks,\n\n##SITE_NAME##\n##SITE_URL##	Hi ##USERNAME##,\n\n your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## will be delivered shortly. Thanks for using ##SITE_NAME##.\n  \nOrder Details:\n##ORDER_URL##\n\nThanks,\n\n##SITE_NAME##\n##SITE_URL##	Delivery Person Assigned	##TO_EMAIL##	f	Order/Order	f
27	2017-12-18 13:05:27	2017-12-18 13:05:27	orderProcessing	We will send this mail to user once restaurant accept order request	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Processing  Order	SITE_NAME,USERNAME,AMOUNT,ORDER_NO,CURRENCY_SYMBOL,ORDER_URL	Hi ##USERNAME##,\n\n your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## accepted by ##RESTAURANT## and will be delivered shortly. Thanks for using ##SITE_NAME##.\n  \nOrder Details:\n##ORDER_URL##\n\nThanks,\n\n##SITE_NAME##\n##SITE_URL##	Hi ##USERNAME##,\n\n your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## accepted by ##RESTAURANT## and will be delivered shortly. Thanks for using ##SITE_NAME##.\n  \nOrder Details:\n##ORDER_URL##\n\nThanks,\n\n##SITE_NAME##\n##SITE_URL##	Processing Order	##TO_EMAIL##	f	Order/Order	f
29	2017-12-21 19:06:00	2017-12-21 19:06:00	useraddresturantmail	we will send this mail\ninto admin, when user register as restarunt	##FROM_EMAIL##	##REPLY_TO_EMAIL##	[##SITE_NAME##] Restaurant added mail	SITE_NAME,EMAIL,USERNAME	Hi Admin,\n\n##USERNAME## added the restaurant.\n\nThanks,\n##SITE_NAME##\n##SITE_URL##	Hi Admin,\n\n##USERNAME## added the restaurant.\n\nThanks,\n##SITE_NAME##\n##SITE_URL##	User Registration Resturant Mail	##TO_EMAIL##	f	Restaurant/Restaurant	f
30	2018-02-09 12:30:27	2018-02-09 12:30:27	orderOutForDelivery	We will send this mail to user once order out for delivery	##FROM_EMAIL##	##REPLY_TO_EMAIL##	Order Out For Delivery	SITE_NAME,USERNAME,AMOUNT,ORDER_NO,CURRENCY_SYMBOL,ORDER_URL	Hi ##USERNAME##,\n\n your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## out for delivery, will be delivered shortly. Thanks for using ##SITE_NAME##.\n  \nOrder Details:\n##ORDER_URL##\n\nThanks,\n\n##SITE_NAME##\n##SITE_URL##	Hi ##USERNAME##,\n\n your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## out for delivery, will be delivered shortly. Thanks for using ##SITE_NAME##.\n  \nOrder Details:\n##ORDER_URL##\n\nThanks,\n\n##SITE_NAME##\n##SITE_URL##	Order Out For Delivery	##TO_EMAIL##	f	Order/Order	f
\.


--
-- Name: email_templates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('email_templates_id_seq', 30, true);


--
-- Data for Name: ips; Type: TABLE DATA; Schema: public; Owner: -
--

COPY ips (id, created_at, updated_at, ip, host, city_id, state_id, country_id, latitude, longitude) FROM stdin;
\.


--
-- Name: ips_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('ips_id_seq', 1, true);


--
-- Data for Name: languages; Type: TABLE DATA; Schema: public; Owner: -
--

COPY languages (id, created_at, updated_at, name, iso2, iso3, is_active) FROM stdin;
1	2009-07-01 13:52:24	2009-07-01 13:52:24	Abkhazian	ab	abk	f
2	2009-07-01 13:52:24	2013-07-22 08:17:56	Afar	aa	aar	f
3	2009-07-01 13:52:24	2009-07-01 13:52:24	Afrikaans	af	afr	f
4	2009-07-01 13:52:24	2009-07-01 13:52:24	Akan	ak	aka	f
5	2009-07-01 13:52:25	2009-07-01 13:52:25	Albanian	sq	sqi	f
6	2009-07-01 13:52:24	2009-07-01 13:52:24	Amharic	am	amh	f
7	2009-07-01 13:52:24	2009-07-01 13:52:24	Arabic	ar	ara	f
8	2009-07-01 13:52:24	2009-07-01 13:52:24	Aragonese	an	arg	f
9	2009-07-01 13:52:25	2009-07-01 13:52:25	Armenian	hy	hye	f
10	2009-07-01 13:52:24	2009-07-01 13:52:24	Assamese	as	asm	f
11	2009-07-01 13:52:24	2009-07-01 13:52:24	Avaric	av	ava	f
12	2009-07-01 13:52:24	2009-07-01 13:52:24	Avestan	ae	ave	f
13	2009-07-01 13:52:24	2009-07-01 13:52:24	Aymara	ay	aym	f
14	2009-07-01 13:52:24	2009-07-01 13:52:24	Azerbaijani	az	aze	f
15	2009-07-01 13:52:24	2009-07-01 13:52:24	Bambara	bm	bam	f
16	2009-07-01 13:52:24	2009-07-01 13:52:24	Bashkir	ba	bak	f
17	2009-07-01 13:52:25	2009-07-01 13:52:25	Basque	eu	eus	f
18	2009-07-01 13:52:24	2009-07-01 13:52:24	Belarusian	be	bel	f
19	2009-07-01 13:52:24	2009-07-01 13:52:24	Bengali	bn	ben	f
20	2009-07-01 13:52:24	2009-07-01 13:52:24	Bihari	bh	bih	f
21	2009-07-01 13:52:24	2009-07-01 13:52:24	Bislama	bi	bis	f
22	2009-07-01 13:52:24	2009-07-01 13:52:24	Bosnian	bs	bos	f
23	2009-07-01 13:52:24	2009-07-01 13:52:24	Breton	br	bre	f
24	2009-07-01 13:52:24	2009-07-01 13:52:24	Bulgarian	bg	bul	f
25	2009-07-01 13:52:25	2009-07-01 13:52:25	Burmese	my	mya	f
26	2009-07-01 13:52:24	2011-10-22 08:13:07	Catalan	ca	cat	f
27	2009-07-01 13:52:25	2009-07-01 13:52:25	Chamorro	ch	cha	f
28	2009-07-01 13:52:25	2009-07-01 13:52:25	Chechen	ce	che	f
29	2009-07-01 13:52:25	2009-07-01 13:52:25	Chichewa	ny	nya	f
30	2009-07-01 13:52:25	2009-07-01 13:52:25	Chinese	zh	zho	f
31	2009-07-01 13:52:25	2009-07-01 13:52:25	Church Slavic	cu	chu	f
32	2009-07-01 13:52:25	2009-07-01 13:52:25	Chuvash	cv	chv	f
33	2009-07-01 13:52:25	2009-07-01 13:52:25	Cornish	kw	cor	f
34	2009-07-01 13:52:25	2009-07-01 13:52:25	Corsican	co	cos	f
35	2009-07-01 13:52:25	2009-07-01 13:52:25	Cree	cr	cre	f
36	2009-07-01 13:52:25	2009-07-01 13:52:25	Croatian	hr	hrv	f
37	2009-07-01 13:52:25	2009-07-01 13:52:25	Czech	cs	ces	f
38	2009-07-01 13:52:25	2011-05-23 12:29:53	Danish	da	dan	f
39	2009-07-01 13:52:25	2009-07-01 13:52:25	Divehi	dv	div	f
40	2009-07-01 13:52:25	2009-07-01 13:52:25	Dutch	nl	nld	f
41	2009-07-01 13:52:25	2009-07-01 13:52:25	Dzongkha	dz	dzo	f
43	2009-07-01 13:52:25	2009-07-01 13:52:25	Esperanto	eo	epo	f
44	2009-07-01 13:52:25	2009-07-01 13:52:25	Estonian	et	est	f
45	2009-07-01 13:52:25	2009-07-01 13:52:25	Ewe	ee	ewe	f
46	2009-07-01 13:52:25	2009-07-01 13:52:25	Faroese	fo	fao	f
47	2009-07-01 13:52:25	2009-07-01 13:52:25	Fijian	fj	fij	f
48	2009-07-01 13:52:25	2009-07-01 13:52:25	Finnish	fi	fin	f
50	2009-07-01 13:52:25	2009-07-01 13:52:25	Fulah	ff	ful	f
42	2009-07-01 13:52:25	2009-07-01 13:52:25	English	en	eng	t
49	2009-07-01 13:52:25	2009-07-01 13:52:25	French	fr	fra	t
51	2009-07-01 13:52:25	2009-07-01 13:52:25	Galician	gl	glg	f
52	2009-07-01 13:52:25	2009-07-01 13:52:25	Ganda	lg	lug	f
53	2009-07-01 13:52:25	2009-07-01 13:52:25	Georgian	ka	kat	f
54	2009-07-01 13:52:25	2009-07-01 13:52:25	German	de	deu	f
55	2009-07-01 13:52:25	2009-07-01 13:52:25	Greek	el	ell	f
56	2009-07-01 13:52:25	2009-07-01 13:52:25	GuaranÃƒÆ’Ã	gn	grn	f
57	2009-07-01 13:52:25	2009-07-01 13:52:25	Gujarati	gu	guj	f
58	2009-07-01 13:52:25	2009-07-01 13:52:25	Haitian	ht	hat	f
59	2009-07-01 13:52:25	2009-07-01 13:52:25	Hausa	ha	hau	f
60	2009-07-01 13:52:25	2009-07-01 13:52:25	Hebrew	he	heb	f
61	2009-07-01 13:52:25	2009-07-01 13:52:25	Herero	hz	her	f
62	2009-07-01 13:52:25	2009-07-01 13:52:25	Hindi	hi	hin	f
63	2009-07-01 13:52:25	2009-07-01 13:52:25	Hiri Motu	ho	hmo	f
64	2009-07-01 13:52:25	2009-07-01 13:52:25	Hungarian	hu	hun	f
65	2009-07-01 13:52:25	2009-07-01 13:52:25	Icelandic	is	isl	f
\.


--
-- Name: languages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('languages_id_seq', 67, false);


--
-- Name: money_transfer_account_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('money_transfer_account_id_seq', 1, false);


--
-- Data for Name: money_transfer_accounts; Type: TABLE DATA; Schema: public; Owner: -
--

COPY money_transfer_accounts (id, created_at, updated_at, user_id, account, is_active, is_primary) FROM stdin;
\.


--
-- Data for Name: oauth_clients; Type: TABLE DATA; Schema: public; Owner: -
--

COPY oauth_clients (id, created_at, updated_at, name, api_key, api_secret, is_active) FROM stdin;
1	2016-05-13 15:28:23	2016-05-13 15:28:23	Web	4542632501382585	3f7C4l1Y2b0S6a7L8c1E7B3Jo3	t
\.


--
-- Name: oauth_clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('oauth_clients_id_seq', 2, true);


--
-- Data for Name: order_item_addons; Type: TABLE DATA; Schema: public; Owner: -
--

COPY order_item_addons (id, created_at, updated_at, order_id, order_item_id, restaurant_addon_id, restaurant_menu_addon_price_id, price) FROM stdin;
\.


--
-- Name: order_item_addons_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('order_item_addons_id_seq', 1, true);


--
-- Data for Name: order_items; Type: TABLE DATA; Schema: public; Owner: -
--

COPY order_items (id, created_at, updated_at, order_id, restaurant_menu_id, restaurant_menu_price_id, quantity, price, total_price) FROM stdin;
\.


--
-- Name: order_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('order_items_id_seq', 1, true);


--
-- Data for Name: order_statuses; Type: TABLE DATA; Schema: public; Owner: -
--

COPY order_statuses (id, created_at, updated_at, name) FROM stdin;
1	2016-09-14 16:46:07	2016-09-14 16:46:07	Payment pending
2	2016-09-14 16:46:44	2016-09-14 16:46:44	Payment failed
3	2016-09-14 16:46:44	2016-09-14 16:46:44	Pending
4	2016-09-14 16:46:44	2016-09-14 16:46:44	Rejected
5	2016-09-14 16:46:44	2016-09-14 16:46:44	Processing
6	2016-09-14 16:46:44	2016-09-14 16:46:44	Delivery person assigned
7	2016-09-14 16:46:44	2016-09-14 16:46:44	Delivered
8	2016-09-14 16:46:44	2016-09-14 16:46:44	Reviewed
10	2017-09-30 13:45:39	2017-09-30 13:45:39	Awaiting COD Validation
11	2017-10-12 13:45:39	2017-10-12 13:45:39	Cancel
12	2017-09-30 13:45:39	2017-09-30 13:45:39	Out for delivery
\.


--
-- Name: order_statuses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('order_statuses_id_seq', 12, true);


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: -
--

COPY orders (id, created_at, updated_at, user_id, restaurant_id, restaurant_branch_id, restaurant_delivery_person_id, order_status_id, payment_gateway_id, gateway_id, total_price, delivery_charge, sales_tax, site_fee, user_address_id, address, city_id, state_id, country_id, latitude, longitude, zip_code, comment, later_delivery_date, delivered_date, is_as_soon_as_delivery, is_pickup_or_delivery, success_url, cancel_url, paypal_pay_key, zazpay_pay_key, coupon_id, discount_amount, track_id) FROM stdin;
\.


--
-- Name: orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('orders_id_seq', 1, true);


--
-- Data for Name: pages; Type: TABLE DATA; Schema: public; Owner: -
--

COPY pages (id, created_at, updated_at, title, slug, content, meta_keywords, meta_description, is_active) FROM stdin;
1	2016-05-30 12:17:27	2016-05-30 12:17:27	Privacy Policy	privacy-policy	For each visitor to our Web page our Web server automatically recognizes no information regarding the domain or e-mail address.\r\n\r\nWe collect the e-mail addresses of those who post messages to our bulletin board the e-mail addresses of those who communicate with us via e-mail the e-mail addresses of those who make postings to our chat areas user-specific information on what pages consumers access or visit information volunteered by the consumer such as survey information and/or site registrations name and address telephone number.\r\n\r\nThe information we collect is disclosed when legally required to do so at the request of governmental authorities conducting an investigation to verify or enforce compliance with the policies governing our Website and applicable laws or to protect against misuse or unauthorized use of our Website to a successor entity in connection with a corporate merger consolidation sale of assets or other corporate change respecting the Website.\r\n\r\nWith respect to cookies. We use cookies to record session information such as items that consumers add to their shopping cart.\r\n\r\nIf you do not want to receive e-mail from us in the future please let us know by sending us e-mail at the above address.\r\n\r\nPersons who supply us with their telephone numbers on-line will only receive telephone contact from us with information regarding orders they have placed on-line. Please provide us with your name and phone number. We will be sure your name is removed from the list we share with other organizations.\r\n\r\nWith respect to Ad Servers. We do not partner with or have special relationships with any ad server companies.\r\n\r\nFrom time to time we may use customer information for new unanticipated uses not previously disclosed in our privacy notice. If our information practices change at some time in the future we will post the policy changes to our Web site to notify you of these changes and we will use for these new purposes only data collected from the time of the policy change forward. If you are concerned about how your information is used you should check back at our Web site periodically.\r\n\r\nUpon request we provide site visitors with access to transaction information (e.g. dates on which customers made purchases amounts and types of purchases) that we maintain about them.\r\n\r\nUpon request we offer visitors the ability to have inaccuracies corrected in contact information transaction information.\r\n\r\nWith respect to security. When we transfer and receive certain types of sensitive information such as financial or health information we redirect visitors to a secure server and will notify visitors through a pop-up screen on our site.\r\n\r\nIf you feel that this site is not following its stated information policy you may contact us at the above addresses or phone number.	privacy	privacy,policy	t
2	2016-05-30 12:17:27	2016-05-30 12:17:27	Terms and Conditions	terms-and-conditions	<h1>Web Site Terms and Conditions of Use </h1>\r\n\r\n1. Terms\r\nBy accessing this web site you are agreeing to be bound by these web site Terms and Conditions of Use all applicable laws and regulations and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms you are prohibited from using or accessing this site. The materials contained in this web site are protected by applicable copyright and trade mark law.\r\n\r\n2. Use License\r\n\r\n    Permission is granted to temporarily download one copy of the materials (information or software) on crowdfunding web site for personal non-commercial transitory viewing only. This is the grant of a license not a transfer of title and under this license you may not:\r\n        modify or copy the materials;\r\n        use the materials for any commercial purpose or for any public display (commercial or non-commercial);\r\n        attempt to decompile or reverse engineer any software contained on crowdfunding web site;\r\n        remove any copyright or other proprietary notations from the materials; or\r\n        transfer the materials to another person or mirror the materials on any other server.\r\n    This license shall automatically terminate if you violate any of these restrictions and may be terminated by crowdfunding at any time. Upon terminating your viewing of these materials or upon the termination of this license you must destroy any downloaded materials in your possession whether in electronic or printed format.\r\n\r\n3. Disclaimer\r\nThe materials on crowdfunding web site are provided as is. crowdfunding makes no warranties expressed or implied and hereby disclaims and negates all other warranties including without limitation implied warranties or conditions of merchantability fitness for a particular purpose or non-infringement of intellectual property or other violation of rights. Further crowdfunding does not warrant or make any representations concerning the accuracy likely results or reliability of the use of the materials on its Internet web site or otherwise relating to such materials or on any sites linked to this site.\r\n4. Limitations\r\nIn no event shall crowdfunding or its suppliers be liable for any damages (including without limitation damages for loss of data or profit or due to business interruption) arising out of the use or inability to use the materials on crowdfunding Internet site even if crowdfunding or a crowdfunding authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties or limitations of liability for consequential or incidental damages these limitations may not apply to you.\r\n5. Revisions and Errata\r\nThe materials appearing on crowdfunding web site could include technical typographical or photographic errors. crowdfunding does not warrant that any of the materials on its web site are accurate complete or current. crowdfunding may make changes to the materials contained on its web site at any time without notice. crowdfunding does not however make any commitment to update the materials.\r\n6. Links\r\ncrowdfunding has not reviewed all of the sites linked to its Internet web site and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by crowdfunding of the site. Use of any such linked web site is at the users own risk.\r\n7. Site Terms of Use Modifications\r\ncrowdfunding may revise these terms of use for its web site at any time without notice. By using this web site you are agreeing to be bound by the then current version of these Terms and Conditions of Use.	terms	terms	t
3	2016-05-30 12:24:36	2016-05-30 12:24:36	Acceptable Use Policy	aup	You are independently responsible for complying with all applicable laws in all of your actions related to your use of PayPal’s services, regardless of the purpose of the use. In addition, you must adhere to the terms of this Acceptable Use Policy.\r\n\r\n<h3> Prohibited Activities</h3>\r\n\r\nYou may not use the PayPal service for activities that:\r\n\r\nviolate any law, statute, ordinance or regulation\r\nrelate to sales of (a) narcotics, steroids, certain controlled substances or other products that present a risk to consumer safety, (b) drug paraphernalia, (c) items that encourage, promote, facilitate or instruct others to engage in illegal activity, (d) items that promote hate, violence, racial intolerance, or the financial exploitation of a crime, (e) items that are considered obscene, (f) items that infringe or violate any copyright, trademark, right of publicity or privacy or any other proprietary right under the laws of any jurisdiction, (g) certain sexually oriented materials or services, (h) ammunition, firearms, or certain firearm parts or accessories, or (i) certain weapons or knives regulated under applicable law\r\nrelate to transactions that (a) show the personal information of third parties in violation of applicable law, (b) support pyramid or ponzi schemes, matrix programs, other “get rich quick” schemes or certain multi-level marketing programs, (c) are associated with purchases of real property, annuities or lottery contracts, lay-away systems, off-shore banking or transactions to finance or refinance debts funded by a credit card, (d) are for the sale of certain items before the seller has control or possession of the item, (e) are by payment processors to collect payments on behalf of merchants, (f) are associated with the following Money Service Business Activities: the sale of traveler’s cheques or money orders, currency exchanges or cheque cashing, or (g) provide certain credit repair or debt settlement services\r\ninvolve the sales of products or services identified by government agencies to have a high likelihood of being fraudulent\r\nviolate applicable laws or industry regulations regarding the sale of (a) tobacco products, or (b) prescription drugs and devices\r\ninvolve gambling, gaming and/or any other activity with an entry fee and a prize, including, but not limited to casino games, sports betting, horse or greyhound racing, lottery tickets, other ventures that facilitate gambling, games of skill (whether or not it is legally defined as a lottery) and sweepstakes unless the operator has obtained prior approval from PayPal and the operator and customers are located exclusively in jurisdictions where such activities are permitted by law.\r\n	policy	policy	t
\.


--
-- Name: pages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('pages_id_seq', 4, true);


--
-- Data for Name: payment_gateway_settings; Type: TABLE DATA; Schema: public; Owner: -
--

COPY payment_gateway_settings (id, created_at, updated_at, payment_gateway_id, name, label, description, type, options, test_mode_value, live_mode_value) FROM stdin;
6	2016-08-10 16:38:04	2016-08-10 16:38:04	1	sudopay_merchant_id	ZazPay Merchant ID		text		\N	\N
7	2016-08-10 16:38:04	2016-08-10 16:38:04	1	sudopay_website_id	ZazPay Website ID		text		\N	\N
9	2016-08-10 16:38:04	2016-08-10 16:38:04	1	sudopay_secret_string	ZazPay Secret String		text		\N	\N
8	2016-08-10 16:38:04	2016-08-10 16:38:04	1	sudopay_api_key	ZazPay API Key		text		\N	\N
10	2016-08-10 16:38:04	2016-08-10 16:38:04	1	is_payment_via_api	ZazPay Payment		text		\N	\N
11	2016-08-10 16:38:04	2016-08-10 16:38:04	1	sudopay_subscription_plan	ZazPay Subscription Plan		text		\N	\N
12	2016-08-10 16:38:04	2016-08-10 16:38:04	1	payment_gateway_all_credentials	All Gateway Credentials		text			
13	2017-12-20 19:06:00	2017-12-20 19:06:00	4	paypal_client_id	Client ID	PayPal Client ID	text		AaFSgezSJciunkPSb4CkRXq4peg90miVeOqfckaCsMOw57TcYfxRDnXXSctqWPZEWx-euOKJJ4wz6Hr-	\N
14	2017-12-20 19:06:00	2017-12-20 19:06:00	4	paypal_client_Secret	Client Secret	PayPal Client Secret	text		EGDZ_szCqR9VC1AlrYmN0YnVfsaX6qAVcoF1UI-RuRK5Die_1ji5blzUmUkrQ5ofh5P3v_x6th5mtq7G	\N
\.


--
-- Name: payment_gateway_settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('payment_gateway_settings_id_seq', 14, true);


--
-- Data for Name: payment_gateways; Type: TABLE DATA; Schema: public; Owner: -
--

COPY payment_gateways (id, created_at, updated_at, name, display_name, description, gateway_fees, is_test_mode, is_active, is_enable_for_wallet, plugin) FROM stdin;
1	2016-05-24 16:38:04	2016-05-24 16:38:04	ZazPay	ZazPay	ZazPay payment	0	t	t	f	Common/ZazPay
2	2016-05-24 16:38:04	2016-05-24 16:38:04	Wallet	Wallet	Wallet payment 	0	t	t	t	Common/Wallet
4	2017-09-30 13:45:39	2017-09-30 13:45:39	COD	Cash On Delivery	Cash On Delivery	\N	t	t	f	Common/COD
5	2017-12-20 19:06:00	2017-12-20 19:06:00	Paypal	paypal	Payment through PayPal	0	t	t	f	Common/Paypal
\.


--
-- Name: payment_gateways_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('payment_gateways_id_seq', 5, true);


--
-- Data for Name: provider_users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY provider_users (id, created_at, updated_at, user_id, provider_id, foreign_id, profile_picture_url, access_token, access_token_secret, is_connected) FROM stdin;
\.


--
-- Name: provider_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('provider_users_id_seq', 1, false);


--
-- Data for Name: providers; Type: TABLE DATA; Schema: public; Owner: -
--

COPY providers (id, created_at, updated_at, name, slug, secret_key, api_key, icon_class, button_class, display_order, is_active) FROM stdin;
1	2016-05-28 14:30:49	2016-06-14 09:55:50	Facebook	facebook	703f1ba7d1e37c730fc78133eb356bc2	192562234629073	fa-facebook	btn-facebook	1	f
3	2016-05-28 14:32:26	2016-05-28 14:32:35	Google	google	Y4uK6bviyBB8HE41w-tnuhIt	1049343239400-sbna4or6cns522qiunb0bon6mip6c2mv.apps.googleusercontent.com	fa-google-plus	btn-google	3	t
2	2016-05-28 14:31:37	2016-05-28 14:31:37	Twitter	twitter	r7k6KTkfW2xu6mDwgNmohVh5QLoEG36YbIpB6n2vwr2y1cSFvA	tELI7PJYUN788gOidXCTeYgQ3	fa-twitter	btn-twitter	2	t
\.


--
-- Name: providers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('providers_id_seq', 3, true);


--
-- Data for Name: push_notifications; Type: TABLE DATA; Schema: public; Owner: -
--

COPY push_notifications (id, created_at, updated_at, user_device_id, message_type, message) FROM stdin;
\.


--
-- Name: push_notifications_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('push_notifications_id_seq', 1, false);


--
-- Data for Name: restaurant_addon_items; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_addon_items (id, created_at, updated_at, restaurant_addon_id, name, is_active) FROM stdin;
\.


--
-- Name: restaurant_addon_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_addon_items_id_seq', 1, false);


--
-- Data for Name: restaurant_addons; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_addons (id, created_at, updated_at, restaurant_id, restaurant_category_id, name, is_active, is_multiple) FROM stdin;
\.


--
-- Name: restaurant_addons_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_addons_id_seq', 1, false);


--
-- Data for Name: restaurant_categories; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_categories (id, created_at, updated_at, restaurant_id, name, display_order, is_active, slug) FROM stdin;
\.


--
-- Name: restaurant_categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_categories_id_seq', 1, true);


--
-- Name: restaurant_cuisine_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_cuisine_id_seq', 1, false);


--
-- Data for Name: restaurant_cuisines; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_cuisines (id, created_at, updated_at, restaurant_id, cuisine_id) FROM stdin;
\.


--
-- Data for Name: restaurant_delivery_person_orders; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_delivery_person_orders (id, created_at, updated_at, order_id, restaurant_id, restaurant_branch_id, restaurant_supervisor_id, restaurant_delivery_person_id, comments, is_delivered) FROM stdin;
\.


--
-- Name: restaurant_delivery_person_orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_delivery_person_orders_id_seq', 1, false);


--
-- Data for Name: restaurant_delivery_persons; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_delivery_persons (id, created_at, updated_at, user_id, restaurant_id, restaurant_branch_id, restaurant_supervisor_id, is_active) FROM stdin;
\.


--
-- Name: restaurant_delivery_persons_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_delivery_persons_id_seq', 1, false);


--
-- Data for Name: restaurant_menu_addon_prices; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_menu_addon_prices (id, created_at, updated_at, restaurant_menu_id, restaurant_addon_id, restaurant_addon_item_id, price, is_free, is_active) FROM stdin;
\.


--
-- Name: restaurant_menu_addon_prices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_menu_addon_prices_id_seq', 1, false);


--
-- Data for Name: restaurant_menu_prices; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_menu_prices (id, created_at, updated_at, restaurant_menu_id, price_type_id, price_type_name, price) FROM stdin;
\.


--
-- Name: restaurant_menu_prices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_menu_prices_id_seq', 1, true);


--
-- Data for Name: restaurant_menus; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_menus (id, created_at, updated_at, cuisine_id, restaurant_id, restaurant_category_id, menu_type_id, name, description, display_order, is_addon, is_popular, is_spicy, is_active, color, stock, sold_quantity, slug, ordered_menu_count) FROM stdin;
\.


--
-- Name: restaurant_menus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_menus_id_seq', 1, true);


--
-- Data for Name: restaurant_reviews; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_reviews (id, created_at, updated_at, user_id, order_id, restaurant_id, rating, message, is_active) FROM stdin;
\.


--
-- Name: restaurant_reviews_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_reviews_id_seq', 1, true);


--
-- Data for Name: restaurant_supervisors; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_supervisors (id, created_at, updated_at, user_id, restaurant_id, restaurant_branch_id, is_active) FROM stdin;
\.


--
-- Name: restaurant_supervisors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_supervisors_id_seq', 1, true);


--
-- Data for Name: restaurant_timings; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurant_timings (id, created_at, updated_at, restaurant_id, day, period_type, start_time, end_time) FROM stdin;
\.


--
-- Name: restaurant_timings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurant_timings_id_seq', 1, true);


--
-- Data for Name: restaurants; Type: TABLE DATA; Schema: public; Owner: -
--

COPY restaurants (id, created_at, updated_at, user_id, parent_id, name, slug, phone, mobile, fax, contact_name, contact_phone, website, address, address1, city_id, state_id, country_id, latitude, longitude, hash, zip_code, sales_tax, minimum_order_for_booking, estimated_time_to_delivery, delivery_charge, delivery_miles, total_reviews, avg_rating, total_orders, total_revenue, is_allow_users_to_door_delivery_order, is_allow_users_to_pickup_order, is_allow_users_to_preorder, is_active, is_closed, is_delivered_by_own, mobile_code) FROM stdin;
\.


--
-- Name: restaurants_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('restaurants_id_seq', 1, false);


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY roles (id, created_at, updated_at, name, is_active) FROM stdin;
1	2016-06-13 16:02:55	2016-06-13 16:02:55	Admin	t
2	2016-06-13 16:02:55	2016-05-13 15:28:23	User	t
3	2016-06-13 16:02:55	2016-05-13 15:28:23	Restaurant	t
4	2016-06-13 16:02:55	2016-05-13 15:28:23	Supervisor	t
5	2016-06-13 16:02:55	2016-05-13 15:28:23	DeliveryPerson	t
\.


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('roles_id_seq', 1, false);


--
-- Data for Name: setting_categories; Type: TABLE DATA; Schema: public; Owner: -
--

COPY setting_categories (id, created_at, updated_at, name, description, plugin) FROM stdin;
1	2016-05-30 12:17:27	2016-05-30 12:17:27	System	Manage site name, contact email, from email and reply to email.	\N
2	2016-05-30 12:17:27	2016-05-30 12:17:27	SEO	Manage content, meta data and other information relevant to browsers or search engines.	\N
3	2016-05-30 12:24:36	2016-05-30 12:24:36	Regional, Currency & Language	Manage site default language, currency and date-time format.	\N
4	2016-05-30 12:25:53	2016-05-30 12:25:53	Account	Manage user account related settings	\N
7	2016-05-30 12:17:27	2016-05-30 12:17:27	Third Party API	Manage third party API related settings	\N
5	2016-05-30 12:17:27	2016-05-30 12:17:27	Wallet	Manage wallet related settings.	Common/Wallet
6	2016-05-30 12:17:27	2016-05-30 12:17:27	Withdrawals	Manage withdrawal related settings.	Common/Withdrawal
8	2016-05-30 12:17:27	2016-05-30 12:17:27	Widget	Widgets for header, footer, view page. Widgets can be in iframe and JavaScript embed code, etc (e.g., Twitter Widget, Facebook Like Box, Facebook Feeds Code, Google Ads).	Restaurant/MultiRestaurant
9	2016-09-06 02:44:48	2016-09-06 02:44:48	Revenue	Manage revenue related settings	Order/Order
28	2017-12-18 12:30:27	2017-12-18 12:30:27	Mobile	Here you can manage Mobile related settings.	Order/Mobile
29	2017-12-18 12:30:27	2017-12-18 12:30:27	SMS	Manage SMS Related settingd here.	Order/Sms
30	2016-05-30 12:17:27	2016-05-30 12:17:27	Order	We can manage Order related settings here.	Order/Order
\.


--
-- Name: setting_categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('setting_categories_id_seq', 30, true);


--
-- Data for Name: settings; Type: TABLE DATA; Schema: public; Owner: -
--

COPY settings (id, created_at, updated_at, setting_category_id, name, value, description, type, label, display_order, options, plugin, is_front_end_access) FROM stdin;
1	2016-05-30 12:25:53	2016-05-30 12:24:36	1	SITE_FROM_EMAIL	productdemo.admin@gmail.com	You can change this email address so that 'From' email will be changed in all email communication.	text	From Email Address	1	\N	\N	f
4	2016-05-30 12:25:53	2016-05-30 12:25:53	1	SUPPORT_EMAIL	productdemo.admin@gmail.com	Support email	text	Support Email Address	4	\N	\N	f
28	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_WELCOME_MAIL_AFTER_REGISTER	0	On enabling this feature, users will receive a welcome mail after registration.	checkbox	Enable Sending Welcome Mail After Registration	6	\N	\N	f
15	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_USING_TO_LOGIN	username	You can select the option from the drop-downs to login into the site	select	Login Handle	1	username, email	\N	f
44	2016-08-10 12:25:53	2016-08-12 16:56:47	0	SITE_DOMAIN_SECRET_HASH	a29ffa33-98ef-4ff4-aaa5-4b0cc39da068	Zazpay Domain Secret Hash	text	Zazpay Domain Secret hash	0	\N	\N	f
43	2016-08-10 12:25:53	2016-08-12 16:56:48	0	SITE_IS_WEBSITE_CREATED	1	Zazpay website account created	checkbox	Zazpay Website Account created	0	\N	\N	f
9	2016-05-30 12:17:27	2016-05-30 12:24:36	2	SITE_TRACKING_SCRIPT	<script type="text/javascript"> var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-18572079-3']); _gaq.push(['_setDomainName', '.dev.agriya.com']); _gaq.push(['_setAllowAnchor', true]); _gaq.push(['_trackPageview']); _gaq.push(function() { href = window.location.search; href.replace(/(utm_source|utm_medium|utm_campaign|utm_term|utm_content)+=[^\\&]*/g, '').replace(/\\&+/g, '&').replace(/\\?\\&/g, '?').replace(/(\\?|\\&)$/g, ''); if (history.replaceState) history.replaceState(null, '', location.pathname + href + location.hash);}); (function() { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })(); </script>	This is the site tracker script used for tracking and analyzing the data on how the people are getting into your website. e.g., Google Analytics. <a href="http://www.google.com/analytics" target="_blank">http://www.google.com/analytics</a>	textarea	Site Tracker Code	3	\N	\N	f
10	2016-05-30 12:24:36	2016-05-30 12:17:27	2	SITE_ROBOTS		Content for robots.txt; (search engine) robots specific instructions. Refer, <a href="http://www.robotstxt.org/">http://www.robotstxt.org/</a> for syntax and usage.	textarea	robots.txt	4		\N	f
3	2016-05-30 12:17:27	2016-05-30 12:25:53	1	SITE_REPLY_TO_EMAIL		You can change this email address so that 'Reply To' email will be changed in all email communication.	text	Reply To Email Address	2	\N	\N	f
37	2016-05-30 12:24:36	2016-05-30 12:24:36	8	WIDGET_FOOTER_SCRIPT		This is the footer page script, used for display banners on footer page	textarea	Code	1	\N	Restaurant/MultiRestaurant	f
2	2016-05-30 12:25:53	2016-05-30 12:25:53	1	SITE_CONTACT_EMAIL	productdemo.admin@gmail.com	Contact email	text	Contact Email	3	\N	\N	f
41	2016-08-10 12:25:53	2016-08-29 17:07:42	0	SITE_IS_ENABLE_SUDOPAY_PLUGIN	1	When site purchased ZazPay plugin	checkbox	Enable ZazPay plugin	0	\N	\N	f
5	2016-05-30 12:25:53	2016-05-30 12:25:53	1	SITE_NAME	OFOS	This name will be used in all pages and emails.	text	Site name	1	\N	\N	t
38	2016-05-30 12:24:36	2016-05-30 12:24:36	8	WIDGET_HOME_SCRIPT		This is the browse page script, used for display banners on browse page below ending soon	textarea	Code	1	\N	Restaurant/MultiRestaurant	f
40	2016-05-30 12:17:27	2016-05-30 12:17:27	8	WIDGET_USER_SCRIPT		This is the Header part script, used for display banners on Header	textarea	Code	1	\N	Restaurant/MultiRestaurant	f
39	2016-05-30 12:24:36	2016-05-30 12:24:36	8	WIDGET_VIEW_SCRIPT		Used for display banners on right side of restaurant view page	textarea	Restaurant View Widget	1	\N	Restaurant/MultiRestaurant	f
47	2017-12-18 12:30:27	2017-12-18 12:30:27	28	IPHONE_IS_LIVE	1	iPhone Live Mode\t	text	iPhone Live Mode	1	\N	Order/Mobile	f
48	2017-12-18 12:30:27	2017-12-18 12:30:27	28	PEM_FILE	agriyaTest.pem	Pem File name	text	Pem File name	2	\N	Order/Mobile	f
49	2017-12-18 12:30:27	2017-12-18 12:30:27	28	PEM_PASSWORD	ahsan123	Pem Password	text	Pem Password	3	\N	Order/Mobile	f
50	2017-12-18 12:30:27	2017-12-18 12:30:27	28	API_ACCESS_KEY	AIzaSyBZpIKn6rEsH52qll7fmu_fTY8jute52pA	Android Api Access Key	text	Android Api Access Key	4	\N	Order/Mobile	f
51	2017-12-18 12:30:27	2017-12-18 12:30:27	28	PUSH_NOTIFICATION_FOR_NEW_BOOKING	Your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## has been placed. It is eligible for on-time guarantee and will be delivered with in ##ESTIMATED_TIME_TO_DELIVERY##.	We will send this sms to user for new order placed and the constants variables are ##SITE_NAME##, ##ORDER_NO##, ##CURRENCY_SYMBOL##, ##ESTIMATED_TIME_TO_DELIVERY##.	text	Push Notification For New Booking	5	\N	Order/Mobile	f
52	2017-12-18 12:30:27	2017-12-18 12:30:27	28	PUSH_NOTIFICATION_FOR_BOOKING_ASSIGN_TO_DELIVERY	Your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## will be delivered shortly. Thanks for using ##SITE_NAME##.	We will send this sms to user for their order assigned to delivery and the constants variables are ##SITE_NAME##, ##ORDER_NO##, ##CURRENCY_SYMBOL##, ##AMOUNT##, ##TOTAL_PRICE##, ##DELIVERY_CHARGE##, ##SALES_TAX##.	text	Push Notification For Booking Assign To Delivery	6	\N	Order/Mobile	f
54	2017-12-18 12:30:27	2017-12-18 12:30:27	29	SMS_FOR_NEW_BOOKING	Your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## has been placed. It is eligible for on-time guarantee and will be delivered with in ##ESTIMATED_TIME_TO_DELIVERY##.	we will send this sms to user for new order placed and the constants variables are ##SITE_NAME##, ##BOOKING_DATE##, ##RESTAURANT_NAME##, ##AMOUNT##, ##TOTAL_PRICE##, ##DELIVERY_CHARGE##, ##SALES_TAX##, ##ORDER_NO##, ##CURRENCY_SYMBOL##	text	SMS For New Booking	1	\N	Order/Sms	f
55	2017-12-18 12:30:27	2017-12-18 12:30:27	29	SMS_FOR_BOOKING_ASSIGN_TO_DELIVERY	your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## will be delivered shortly. Thanks for using ##SITE_NAME##.	we will send this sms to user while order assigned to delivery and the constants variables are ##SITE_NAME##, ##BOOKING_DATE##, ##RESTAURANT_NAME##, ##AMOUNT##, ##TOTAL_PRICE##, ##DELIVERY_CHARGE##, ##SALES_TAX##, ##ORDER_NO##, ##CURRENCY_SYMBOL##	text	SMS For Booking Assign To Delivery	2	\N	Order/Sms	f
56	2017-12-18 12:30:27	2017-12-18 12:30:27	29	SMS_FOR_BOOKING_DELIVERED	Your order ##ORDER_NO## has been delivered! We hope you enjoyed the ##SITE_NAME## Assured Experience.	 we will send this sms after booking delivered to user and the constants variables are ##SITE_NAME##, ##BOOKING_DATE##, ##RESTAURANT_NAME##, ##AMOUNT##, ##TOTAL_PRICE##, ##DELIVERY_CHARGE##, ##SALES_TAX##, ##ORDER_NO##, ##CURRENCY_SYMBOL##	text	SMS For Booking Delivered	3	\N	Order/Sms	f
53	2017-12-18 12:30:27	2017-12-18 12:30:27	28	PUSH_NOTIFICATION_FOR_BOOKING_DELIVERED	Your order ##ORDER_NO## has been delivered! We hope you enjoyed the ##SITE_NAME## Assured Experience.	We will send this sms to user after booking delivered and the constants variables are ##SITE_NAME##, ##BOOKING_DATE##, ##RESTAURANT_NAME##, ##AMOUNT##, ##TOTAL_PRICE##, ##DELIVERY_CHARGE##, ##SALES_TAX##, ##ORDER_NO##, ##CURRENCY_SYMBOL##	text	Push Notification For Booking Delivered	7	\N	Order/Mobile	f
20	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_EMAIL_VERIFICATION_FOR_REGISTER	0	On enabling this feature, the users are required to verify their email address which will be provided by them during registration. (Users cannot login until the email address is verified)	checkbox	Enable Email Verification After Registration	2	\N	\N	t
19	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER	0	On enabling this feature, the user will not be able to login until the Admin (that will be you) approves their registration.	checkbox	Enable Administrator Approval After Registration	1	\N	\N	t
27	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_LOGOUT_AFTER_CHANGE_PASSWORD	0	By enabling this feature, When user changes the password, he will automatically log-out.	checkbox	Enable User to Logout after Password Change	5	\N	\N	t
21	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_AUTO_LOGIN_AFTER_REGISTER	0	On enabling this feature, users will be automatically logged-in after registration. (Only when "Email Verification" & "Admin Approval" is disabled)	checkbox	Enable Auto Login After Registration	3	\N	\N	t
12	2016-05-30 12:25:53	2016-05-30 12:24:36	3	CURRENCY_SYMBOL	$	Site Currency symbol of PayPal Currency Code. eg. $ for USD	text	Site Currency Symbol	1	\N	\N	t
13	2016-05-30 12:17:27	2016-05-30 12:17:27	3	CURRENCY_CODE	USD	PayPal doesnt support all currencies; refer, <a href="https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside">https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside</a> for list of supported currencies in PayPal. The selected currency will be used as site default currency. (All payments, transaction will use this currency).	select	Currency Code	2	AUD,BRL,CAD,CZK,DKK,EUR,HKD,HUF,ILS,JPY,MXN,NOK,NZD,PHP,PLN,GBP,SGD,SEK,CHF,TWD,THB,TRY,USD	\N	t
8	2016-05-30 12:24:36	2016-05-30 12:17:27	2	META_DESCRIPTION	Online food ordering system	These are the short descriptions for your site which will be used by the search engines on the search result pages to display preview snippets for a given page.	textarea	Description	2		\N	t
11	2016-05-30 12:25:53	2016-05-30 12:25:53	3	SITE_LANGUAGE	en	The selected language will be used as default language all over the site.	select	Site Language 	1	\N	\N	t
14	2016-05-30 12:24:36	2016-05-30 12:24:36	4	USER_IS_ALLOW_SWITCH_LANGUAGE	1	On enabling this feature, users can change site language to their choice.	checkbox	Enable User to Switch Language	1	\N	\N	t
29	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_ADMIN_MAIL_AFTER_REGISTER	0	On enabling this feature, notification mail will be sent to administrator on each registration.	checkbox	Enable Notify Administrator on Each Registration	7	\N	\N	t
30	2016-05-30 12:17:27	2016-05-30 12:17:27	4	USER_IS_CAPTCHA_ENABLED_FORGOT_PASSWORD	0	On enabling this feature, captcha will display forgot password page.	checkbox	Enable Captcha Forgot password	8	\N	\N	t
32	2016-09-02 12:25:53	2016-09-02 12:25:53	1	SITE_TWITTER_URL	https://twitter.com/agriya		text	Site Twitter URL	4		\N	t
31	2016-09-02 12:25:53	2016-09-02 12:25:53	1	SITE_FACEBOOK_URL	https://www.facebook.com/agriya		text	Site Facebook URL	4		\N	t
42	2016-09-02 12:25:53	2016-09-02 12:25:53	1	SITE_YOUTUBE_URL	https://www.youtube.com/channel/UCcxmjGrb-E8CKXFv2RKOG5A		text	Site Youtube URL	4		\N	t
24	2016-05-30 12:24:36	2016-05-30 12:24:36	6	USER_MINIMUM_WITHDRAW_AMOUNT	2	This is the minimum amount a user can withdraw from their wallet.	text	Minimum Withdrawal Amount	1	\N	Common/Withdrawal	t
25	2016-05-30 12:17:27	2016-05-30 12:17:27	6	USER_MAXIMUM_WITHDRAW_AMOUNT	10000	This is the maximum amount a user can withdraw from their wallet.	text	Maximum Withdrawal Amount	2	\N	Common/Withdrawal	t
26	2016-05-30 12:24:36	2016-05-30 12:24:36	9	SITE_COMMISSION	10	Site commission percentage wise	text	Site commission	1	\N	Order/Order	t
7	2016-05-30 12:17:27	2016-05-30 12:24:36	2	META_KEYWORDS	Agriya, OFOS, Online Food Ordering System	These are the keywords used for improving search engine results of your site. (Comma separated texts for multiple keywords.)	text	Keywords	1	\N	\N	t
46	2017-01-05 13:11:51	2017-01-05 13:11:51	1	SITE_ENABLED_PLUGINS	Common/Paypal,Order/Coupon,Common/Translation,Common/Wallet,Common/Withdrawal,Common/ZazPay,Order/Mobile,Order/Order,Order/OutsourcedDelivery,Order/OwnDelivery,Order/Sms,Order/Supervisor,Order/Review,Restaurant/MultiRestaurant	\N	text	Site Plugin	1	\N	\N	t
22	2016-05-30 12:17:27	2016-05-30 12:17:27	5	WALLET_MIN_WALLET_AMOUNT	10	This is the minimum amount a user can add to his wallet.	text	Minimum wallet amount	1	\N	Common/Wallet	t
23	2016-05-30 12:24:36	2016-05-30 12:24:36	5	WALLET_MAX_WALLET_AMOUNT	20000	This is the maximum amount a user can add to his wallet. (If left empty, then, no maximum amount restrictions).	text	Maximum wallet amount	2	\N	Common/Wallet	t
57	2017-12-18 13:05:27	2017-12-18 13:05:27	1	SITE_OFFLINE	0	If Site offline is 1 then Site display as offline.	text	Site Offline?	10	\N	\N	t
58	2016-05-30 12:17:27	2016-05-30 12:17:27	30	DISABLE_PRE_BOOK	0	Overall site pre-book Will be disabled.	text	Disable pre-book?	11	\N	Order/Order	t
59	2017-12-28 19:06:00	2017-12-28 19:06:00	4	USER_IS_CAPTCHA_ENABLED_REGISTER	0	On enabling this feature, captcha will display registration page.	checkbox	Enable Captcha Registration	10	\N	\N	t
60	2017-12-28 19:06:00	2017-12-28 19:06:00	7	CAPTCHA_SITE_KEY	6LctcT8UAAAAAOKkmE0tPtOVd2vpgcoxIbBuQkj9	Captcha site key	text	Captcha Site Key	11	\N	\N	t
61	2017-12-28 19:06:00	2017-12-28 19:06:00	7	CAPTCHA_SECRET_KEY	6LctcT8UAAAAAPyOY6a-SEnVRgmBDLeQwBXCITLd	Captcha Secret Key		Captcha Secret Key	12	\N	\N	t
62	2018-02-09 12:30:27	2018-02-09 12:30:27	29	SMS_FOR_BOOKING_OUT_FOR_DELIVERY	your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## out for delivery, will be delivered shortly. Thanks for using ##SITE_NAME##.	we will send this sms to user while order out for delivery and the constants variables are ##SITE_NAME##, ##BOOKING_DATE##, ##RESTAURANT_NAME##, ##AMOUNT##, ##TOTAL_PRICE##, ##DELIVERY_CHARGE##, ##SALES_TAX##, ##ORDER_NO##, ##CURRENCY_SYMBOL##	text	SMS For Booking Out For Delivery	4	\N	\N	f
63	2018-02-09 12:30:27	2018-02-09 12:30:27	28	PUSH_NOTIFICATION_FOR_BOOKING_OUT_FOR_DELIVERY	Your order ##ORDER_NO## of ##CURRENCY_SYMBOL## ##AMOUNT## out for delivery, will be delivered shortly. Thanks for using ##SITE_NAME##.	We will send this sms to user for their order out for delivery and the constants variables are ##SITE_NAME##, ##ORDER_NO##, ##CURRENCY_SYMBOL##, ##AMOUNT##, ##TOTAL_PRICE##, ##DELIVERY_CHARGE##, ##SALES_TAX##.	text	Push Notification For Booking Out For Delivery	8	\N	\N	f
\.


--
-- Name: settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('settings_id_seq', 63, true);


--
-- Data for Name: states; Type: TABLE DATA; Schema: public; Owner: -
--

COPY states (id, created_at, updated_at, country_id, name, is_active) FROM stdin;
\.


--
-- Name: states_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('states_id_seq', 1, true);


--
-- Data for Name: sudopay_ipn_logs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY sudopay_ipn_logs (id, created_at, updated_at, ip, post_variable) FROM stdin;
\.


--
-- Name: sudopay_ipn_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('sudopay_ipn_logs_id_seq', 1, false);


--
-- Data for Name: sudopay_payment_gateways; Type: TABLE DATA; Schema: public; Owner: -
--

COPY sudopay_payment_gateways (id, created_at, updated_at, sudopay_gateway_name, sudopay_gateway_id, sudopay_payment_group_id, sudopay_gateway_details, is_marketplace_supported) FROM stdin;
\.


--
-- Name: sudopay_payment_gateways_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('sudopay_payment_gateways_id_seq', 1, true);


--
-- Data for Name: sudopay_payment_gateways_users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY sudopay_payment_gateways_users (id, created_at, updated_at, user_id, sudopay_payment_gateway_id) FROM stdin;
\.


--
-- Name: sudopay_payment_gateways_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('sudopay_payment_gateways_users_id_seq', 1, false);


--
-- Data for Name: sudopay_payment_groups; Type: TABLE DATA; Schema: public; Owner: -
--

COPY sudopay_payment_groups (id, created_at, updated_at, sudopay_group_id, name, thumb_url) FROM stdin;
\.


--
-- Name: sudopay_payment_groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('sudopay_payment_groups_id_seq', 1, true);


--
-- Data for Name: sudopay_transaction_logs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY sudopay_transaction_logs (id, created_at, updated_at, class, foreign_id, sudopay_pay_key, merchant_id, gateway_id, status, payment_type, buyer_id, buyer_email, buyer_address, amount, payment_id) FROM stdin;
\.


--
-- Name: sudopay_transaction_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('sudopay_transaction_logs_id_seq', 1, true);


--
-- Data for Name: transaction_types; Type: TABLE DATA; Schema: public; Owner: -
--

COPY transaction_types (id, created_at, updated_at, name, is_credit, is_credit_to_other_user, is_credit_to_admin, message, message_for_other_user, message_for_admin, transaction_variables) FROM stdin;
1	2016-08-10 16:38:04	2016-08-10 16:38:04	Amount added to wallet	t	f	f	Amount added to wallet	\N	##USER## added amount to own wallet	USER
2	2016-08-10 16:38:04	2016-08-10 16:38:04	Order placed	f	f	t	Order placed ###ORDER_ID##	##USER## placed an order ###ORDER_ID##	##USER## placed an order ###ORDER_ID##	USER, ORDER_ID
4	2016-08-10 16:38:04	2016-08-10 16:38:04	Paid amount to restaurant	f	t	f	###ORDER_ID## amount paid	###ORDER_ID## amount paid to ##RESTAURANT##	###ORDER_ID## amount paid to ##RESTAURANT##	ORDER_ID, RESTAURANT
3	2016-08-10 16:38:04	2016-08-10 16:38:04	Refund for rejected order	f	t	f	##RESTAURANT## rejected order ###ORDER_ID##	You have rejected order ###ORDER_ID##	##RESTAURANT## rejected order ###ORDER_ID##	RESTAURANT, ORDER_ID
\.


--
-- Name: transaction_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('transaction_types_id_seq', 1, false);


--
-- Data for Name: transactions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY transactions (id, created_at, updated_at, user_id, other_user_id, restaurant_id, amount, foreign_id, class, transaction_type_id, payment_gateway_id, gateway_fees) FROM stdin;
\.


--
-- Name: transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('transactions_id_seq', 1, false);


--
-- Data for Name: user_add_wallet_amounts; Type: TABLE DATA; Schema: public; Owner: -
--

COPY user_add_wallet_amounts (id, created_at, updated_at, user_id, description, amount, payment_gateway_id, sudopay_gateway_id, sudopay_revised_amount, sudopay_token, is_success) FROM stdin;
\.


--
-- Name: user_add_wallet_amounts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_add_wallet_amounts_id_seq', 1, false);


--
-- Name: user_address_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_address_id_seq', 1, true);


--
-- Data for Name: user_addresses; Type: TABLE DATA; Schema: public; Owner: -
--

COPY user_addresses (id, created_at, updated_at, user_id, title, building_address, address2, landmark, city_id, state_id, country_id, zip_code, latitude, longitude, hash, is_active) FROM stdin;
\.


--
-- Data for Name: user_cash_withdrawals; Type: TABLE DATA; Schema: public; Owner: -
--

COPY user_cash_withdrawals (id, created_at, updated_at, user_id, money_transfer_account_id, amount, remark, status) FROM stdin;
\.


--
-- Name: user_cash_withdrawals_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_cash_withdrawals_id_seq', 1, false);


--
-- Data for Name: user_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY user_tokens (id, created_at, updated_at, user_id, oauth_client_id, token, expires) FROM stdin;
\.


--
-- Name: user_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('user_tokens_id_seq', 1, false);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY users (id, created_at, updated_at, username, email, password, role_id, provider_id, first_name, last_name, gender_id, dob, about_me, address, address1, phone, mobile, city_id, state_id, country_id, latitude, longitude, available_wallet_amount, total_orders, total_reviews, zip_code, last_logged_in_time, last_login_ip_id, is_email_confirmed, is_agree_terms_conditions, is_subscribed, is_active, is_created_from_order_page, mobile_code) FROM stdin;
1	2016-06-14 18:20:16	2016-09-06 08:21:51	admin	productdemo.admin@gmail.com	$2y$12$7Bezs1GQsctRnC80lGMC7e4Q.g2opvnIyURlXhFqQ7urzI1voVp5y	1	0	\N	\N	\N	\N	\N	\N	\N	\N	123456789	0	0	0	\N	\N	0	0	0	\N	2016-09-06 08:21:51	0	t	f	t	t	f	\N
\.


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('users_id_seq', 2, true);


--
-- Data for Name: wallet_transaction_logs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY wallet_transaction_logs (id, created_at, updated_at, amount, foreign_id, class, status, payment_type) FROM stdin;
\.


--
-- Name: wallet_transaction_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('wallet_transaction_logs_id_seq', 1, false);


--
-- Data for Name: wallets; Type: TABLE DATA; Schema: public; Owner: -
--

COPY wallets (id, created_at, updated_at, user_id, amount, payment_gateway_id, gateway_id, is_payment_completed, success_url, cancel_url, paypal_pay_key, zazpay_pay_key) FROM stdin;
\.


--
-- Name: wallets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('wallets_id_seq', 1, false);


--
-- Name: attachments_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY attachments
    ADD CONSTRAINT attachments_id PRIMARY KEY (id);


--
-- Name: banned_ips_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY banned_ips
    ADD CONSTRAINT banned_ips_id PRIMARY KEY (id);


--
-- Name: cart_addons_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cart_addons
    ADD CONSTRAINT cart_addons_id PRIMARY KEY (id);


--
-- Name: carts_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY carts
    ADD CONSTRAINT carts_id PRIMARY KEY (id);


--
-- Name: cities_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cities
    ADD CONSTRAINT cities_id PRIMARY KEY (id);


--
-- Name: contacts_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY contacts
    ADD CONSTRAINT contacts_id PRIMARY KEY (id);


--
-- Name: countries_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY countries
    ADD CONSTRAINT countries_id PRIMARY KEY (id);


--
-- Name: coupons_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY coupons
    ADD CONSTRAINT coupons_id PRIMARY KEY (id);


--
-- Name: cuisines_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cuisines
    ADD CONSTRAINT cuisines_id PRIMARY KEY (id);


--
-- Name: device_details_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY device_details
    ADD CONSTRAINT device_details_id PRIMARY KEY (id);


--
-- Name: email_templates_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY email_templates
    ADD CONSTRAINT email_templates_id PRIMARY KEY (id);


--
-- Name: ips_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ips
    ADD CONSTRAINT ips_id PRIMARY KEY (id);


--
-- Name: languages_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY languages
    ADD CONSTRAINT languages_id PRIMARY KEY (id);


--
-- Name: money_transfer_accounts_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY money_transfer_accounts
    ADD CONSTRAINT money_transfer_accounts_id PRIMARY KEY (id);


--
-- Name: oauth_clients_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY oauth_clients
    ADD CONSTRAINT oauth_clients_id PRIMARY KEY (id);


--
-- Name: order_item_addons_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_item_addons
    ADD CONSTRAINT order_item_addons_id PRIMARY KEY (id);


--
-- Name: order_items_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_id PRIMARY KEY (id);


--
-- Name: order_statuses_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_statuses
    ADD CONSTRAINT order_statuses_id PRIMARY KEY (id);


--
-- Name: orders_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_id PRIMARY KEY (id);


--
-- Name: pages_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY pages
    ADD CONSTRAINT pages_id PRIMARY KEY (id);


--
-- Name: payment_gateway_settings_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY payment_gateway_settings
    ADD CONSTRAINT payment_gateway_settings_id PRIMARY KEY (id);


--
-- Name: payment_gateways_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY payment_gateways
    ADD CONSTRAINT payment_gateways_id PRIMARY KEY (id);


--
-- Name: provider_users_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY provider_users
    ADD CONSTRAINT provider_users_id PRIMARY KEY (id);


--
-- Name: providers_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY providers
    ADD CONSTRAINT providers_id PRIMARY KEY (id);


--
-- Name: push_notifications_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY push_notifications
    ADD CONSTRAINT push_notifications_id PRIMARY KEY (id);


--
-- Name: restaurant_addon_items_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_addon_items
    ADD CONSTRAINT restaurant_addon_items_id PRIMARY KEY (id);


--
-- Name: restaurant_addons_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_addons
    ADD CONSTRAINT restaurant_addons_id PRIMARY KEY (id);


--
-- Name: restaurant_categories_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_categories
    ADD CONSTRAINT restaurant_categories_id PRIMARY KEY (id);


--
-- Name: restaurant_cuisine_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_cuisines
    ADD CONSTRAINT restaurant_cuisine_id PRIMARY KEY (id);


--
-- Name: restaurant_delivery_person_orders_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_delivery_person_orders
    ADD CONSTRAINT restaurant_delivery_person_orders_id PRIMARY KEY (id);


--
-- Name: restaurant_delivery_persons_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_delivery_persons
    ADD CONSTRAINT restaurant_delivery_persons_id PRIMARY KEY (id);


--
-- Name: restaurant_menu_addon_prices_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_menu_addon_prices
    ADD CONSTRAINT restaurant_menu_addon_prices_id PRIMARY KEY (id);


--
-- Name: restaurant_menu_prices_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_menu_prices
    ADD CONSTRAINT restaurant_menu_prices_id PRIMARY KEY (id);


--
-- Name: restaurant_menus_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_menus
    ADD CONSTRAINT restaurant_menus_id PRIMARY KEY (id);


--
-- Name: restaurant_supervisors_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_supervisors
    ADD CONSTRAINT restaurant_supervisors_id PRIMARY KEY (id);


--
-- Name: restaurant_timings_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_timings
    ADD CONSTRAINT restaurant_timings_id PRIMARY KEY (id);


--
-- Name: restaurants_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurants
    ADD CONSTRAINT restaurants_id PRIMARY KEY (id);


--
-- Name: roles_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_id PRIMARY KEY (id);


--
-- Name: setting_categories_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY setting_categories
    ADD CONSTRAINT setting_categories_id PRIMARY KEY (id);


--
-- Name: settings_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY settings
    ADD CONSTRAINT settings_id PRIMARY KEY (id);


--
-- Name: states_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY states
    ADD CONSTRAINT states_id PRIMARY KEY (id);


--
-- Name: sudopay_ipn_logs_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY sudopay_ipn_logs
    ADD CONSTRAINT sudopay_ipn_logs_id PRIMARY KEY (id);


--
-- Name: sudopay_payment_gateways_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY sudopay_payment_gateways
    ADD CONSTRAINT sudopay_payment_gateways_id PRIMARY KEY (id);


--
-- Name: sudopay_payment_gateways_users_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY sudopay_payment_gateways_users
    ADD CONSTRAINT sudopay_payment_gateways_users_id PRIMARY KEY (id);


--
-- Name: sudopay_payment_groups_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY sudopay_payment_groups
    ADD CONSTRAINT sudopay_payment_groups_id PRIMARY KEY (id);


--
-- Name: sudopay_transaction_logs_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY sudopay_transaction_logs
    ADD CONSTRAINT sudopay_transaction_logs_id PRIMARY KEY (id);


--
-- Name: transaction_types_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY transaction_types
    ADD CONSTRAINT transaction_types_id PRIMARY KEY (id);


--
-- Name: transactions_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY transactions
    ADD CONSTRAINT transactions_id PRIMARY KEY (id);


--
-- Name: user_addresses_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_addresses
    ADD CONSTRAINT user_addresses_id PRIMARY KEY (id);


--
-- Name: user_cash_withdrawals_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_cash_withdrawals
    ADD CONSTRAINT user_cash_withdrawals_id PRIMARY KEY (id);


--
-- Name: user_reviews_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_reviews
    ADD CONSTRAINT user_reviews_id PRIMARY KEY (id);


--
-- Name: user_tokens_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_tokens
    ADD CONSTRAINT user_tokens_id PRIMARY KEY (id);


--
-- Name: users_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_id PRIMARY KEY (id);


--
-- Name: wallet_transaction_logs_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY wallet_transaction_logs
    ADD CONSTRAINT wallet_transaction_logs_id PRIMARY KEY (id);


--
-- Name: wallets_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY wallets
    ADD CONSTRAINT wallets_id PRIMARY KEY (id);


--
-- Name: attachments_class; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX attachments_class ON attachments USING btree (class);


--
-- Name: attachments_foreign_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX attachments_foreign_id ON attachments USING btree (foreign_id);


--
-- Name: cart_addons_cart_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cart_addons_cart_id ON cart_addons USING btree (cart_id);


--
-- Name: cart_addons_restaurant_addon_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cart_addons_restaurant_addon_id ON cart_addons USING btree (restaurant_addon_id);


--
-- Name: cart_addons_restaurant_addon_item_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cart_addons_restaurant_addon_item_id ON cart_addons USING btree (restaurant_addon_item_id);


--
-- Name: cart_addons_restaurant_menu_addon_price_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cart_addons_restaurant_menu_addon_price_id ON cart_addons USING btree (restaurant_menu_addon_price_id);


--
-- Name: carts_cookie_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX carts_cookie_id ON carts USING btree (cookie_id);


--
-- Name: carts_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX carts_restaurant_id ON carts USING btree (restaurant_id);


--
-- Name: carts_restaurant_menu_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX carts_restaurant_menu_id ON carts USING btree (restaurant_menu_id);


--
-- Name: carts_restaurant_menu_price_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX carts_restaurant_menu_price_id ON carts USING btree (restaurant_menu_price_id);


--
-- Name: carts_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX carts_user_id ON carts USING btree (user_id);


--
-- Name: cities_country_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cities_country_id ON cities USING btree (country_id);


--
-- Name: cities_state_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cities_state_id ON cities USING btree (state_id);


--
-- Name: coupons_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX coupons_restaurant_id ON coupons USING btree (restaurant_id);


--
-- Name: coupons_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX coupons_user_id ON coupons USING btree (user_id);


--
-- Name: device_details_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX device_details_user_id ON device_details USING btree (user_id);


--
-- Name: ips_city_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX ips_city_id ON ips USING btree (city_id);


--
-- Name: ips_country_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX ips_country_id ON ips USING btree (country_id);


--
-- Name: ips_state_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX ips_state_id ON ips USING btree (state_id);


--
-- Name: languages_iso2; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX languages_iso2 ON languages USING btree (iso2);


--
-- Name: money_transfer_accounts_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX money_transfer_accounts_restaurant_id ON money_transfer_accounts USING btree (user_id);


--
-- Name: oauth_clients_client_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_clients_client_id ON oauth_clients USING btree (api_key);


--
-- Name: order_item_addons_order_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX order_item_addons_order_id ON order_item_addons USING btree (order_id);


--
-- Name: order_item_addons_order_item_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX order_item_addons_order_item_id ON order_item_addons USING btree (order_item_id);


--
-- Name: order_item_addons_restaurant_addon_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX order_item_addons_restaurant_addon_id ON order_item_addons USING btree (restaurant_addon_id);


--
-- Name: order_item_addons_restaurant_menu_addon_price_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX order_item_addons_restaurant_menu_addon_price_id ON order_item_addons USING btree (restaurant_menu_addon_price_id);


--
-- Name: order_items_order_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX order_items_order_id ON order_items USING btree (order_id);


--
-- Name: order_items_restaurant_menu_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX order_items_restaurant_menu_id ON order_items USING btree (restaurant_menu_id);


--
-- Name: order_items_restaurant_menu_price_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX order_items_restaurant_menu_price_id ON order_items USING btree (restaurant_menu_price_id);


--
-- Name: orders_city_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX orders_city_id ON orders USING btree (city_id);


--
-- Name: orders_country_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX orders_country_id ON orders USING btree (country_id);


--
-- Name: orders_order_status_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX orders_order_status_id ON orders USING btree (order_status_id);


--
-- Name: orders_payment_gateway_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX orders_payment_gateway_id ON orders USING btree (payment_gateway_id);


--
-- Name: orders_restaurant_branch_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX orders_restaurant_branch_id ON orders USING btree (restaurant_branch_id);


--
-- Name: orders_restaurant_delivery_person_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX orders_restaurant_delivery_person_id ON orders USING btree (restaurant_delivery_person_id);


--
-- Name: orders_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX orders_restaurant_id ON orders USING btree (restaurant_id);


--
-- Name: orders_state_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX orders_state_id ON orders USING btree (state_id);


--
-- Name: orders_user_address_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX orders_user_address_id ON orders USING btree (user_address_id);


--
-- Name: pages_slug; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX pages_slug ON pages USING btree (slug);


--
-- Name: payment_gateway_settings_payment_gateway_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_gateway_settings_payment_gateway_id ON payment_gateway_settings USING btree (payment_gateway_id);


--
-- Name: provider_users_foreign_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX provider_users_foreign_id ON provider_users USING btree (foreign_id);


--
-- Name: provider_users_provider_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX provider_users_provider_id ON provider_users USING btree (provider_id);


--
-- Name: provider_users_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX provider_users_user_id ON provider_users USING btree (user_id);


--
-- Name: push_notifications_user_device_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX push_notifications_user_device_id ON push_notifications USING btree (user_device_id);


--
-- Name: restaurant_addon_items_restaurant_addon_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_addon_items_restaurant_addon_id ON restaurant_addon_items USING btree (restaurant_addon_id);


--
-- Name: restaurant_addons_restaurant_category_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_addons_restaurant_category_id ON restaurant_addons USING btree (restaurant_category_id);


--
-- Name: restaurant_addons_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_addons_restaurant_id ON restaurant_addons USING btree (restaurant_id);


--
-- Name: restaurant_categories_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_categories_restaurant_id ON restaurant_categories USING btree (restaurant_id);


--
-- Name: restaurant_cuisine_cuisine_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_cuisine_cuisine_id ON restaurant_cuisines USING btree (cuisine_id);


--
-- Name: restaurant_cuisine_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_cuisine_restaurant_id ON restaurant_cuisines USING btree (restaurant_id);


--
-- Name: restaurant_delivery_person_orders_order_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_delivery_person_orders_order_id ON restaurant_delivery_person_orders USING btree (order_id);


--
-- Name: restaurant_delivery_person_orders_restaurant_branch_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_delivery_person_orders_restaurant_branch_id ON restaurant_delivery_person_orders USING btree (restaurant_branch_id);


--
-- Name: restaurant_delivery_person_orders_restaurant_delivery_person_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_delivery_person_orders_restaurant_delivery_person_id ON restaurant_delivery_person_orders USING btree (restaurant_delivery_person_id);


--
-- Name: restaurant_delivery_person_orders_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_delivery_person_orders_restaurant_id ON restaurant_delivery_person_orders USING btree (restaurant_id);


--
-- Name: restaurant_delivery_person_orders_restaurant_supervisor_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_delivery_person_orders_restaurant_supervisor_id ON restaurant_delivery_person_orders USING btree (restaurant_supervisor_id);


--
-- Name: restaurant_delivery_persons_restaurant_branch_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_delivery_persons_restaurant_branch_id ON restaurant_delivery_persons USING btree (restaurant_branch_id);


--
-- Name: restaurant_delivery_persons_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_delivery_persons_restaurant_id ON restaurant_delivery_persons USING btree (restaurant_id);


--
-- Name: restaurant_delivery_persons_restaurant_supervisor_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_delivery_persons_restaurant_supervisor_id ON restaurant_delivery_persons USING btree (restaurant_supervisor_id);


--
-- Name: restaurant_delivery_persons_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_delivery_persons_user_id ON restaurant_delivery_persons USING btree (user_id);


--
-- Name: restaurant_menu_addon_prices_restaurant_addon_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_menu_addon_prices_restaurant_addon_id ON restaurant_menu_addon_prices USING btree (restaurant_addon_id);


--
-- Name: restaurant_menu_addon_prices_restaurant_addon_item_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_menu_addon_prices_restaurant_addon_item_id ON restaurant_menu_addon_prices USING btree (restaurant_addon_item_id);


--
-- Name: restaurant_menu_addon_prices_restaurant_menu_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_menu_addon_prices_restaurant_menu_id ON restaurant_menu_addon_prices USING btree (restaurant_menu_id);


--
-- Name: restaurant_menu_prices_price_type_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_menu_prices_price_type_id ON restaurant_menu_prices USING btree (price_type_id);


--
-- Name: restaurant_menu_prices_restaurant_menu_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_menu_prices_restaurant_menu_id ON restaurant_menu_prices USING btree (restaurant_menu_id);


--
-- Name: restaurant_menus_cuisine_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_menus_cuisine_id ON restaurant_menus USING btree (cuisine_id);


--
-- Name: restaurant_menus_menu_type_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_menus_menu_type_id ON restaurant_menus USING btree (menu_type_id);


--
-- Name: restaurant_menus_restaurant_category_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_menus_restaurant_category_id ON restaurant_menus USING btree (restaurant_category_id);


--
-- Name: restaurant_menus_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_menus_restaurant_id ON restaurant_menus USING btree (restaurant_id);


--
-- Name: restaurant_reviews_order_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_reviews_order_id ON restaurant_reviews USING btree (order_id);


--
-- Name: restaurant_reviews_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_reviews_restaurant_id ON restaurant_reviews USING btree (restaurant_id);


--
-- Name: restaurant_reviews_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_reviews_user_id ON restaurant_reviews USING btree (user_id);


--
-- Name: restaurant_supervisors_restaurant_branch_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_supervisors_restaurant_branch_id ON restaurant_supervisors USING btree (restaurant_branch_id);


--
-- Name: restaurant_supervisors_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_supervisors_restaurant_id ON restaurant_supervisors USING btree (restaurant_id);


--
-- Name: restaurant_supervisors_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_supervisors_user_id ON restaurant_supervisors USING btree (user_id);


--
-- Name: restaurant_timings_restaurant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurant_timings_restaurant_id ON restaurant_timings USING btree (restaurant_id);


--
-- Name: restaurants_city_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurants_city_id ON restaurants USING btree (city_id);


--
-- Name: restaurants_country_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurants_country_id ON restaurants USING btree (country_id);


--
-- Name: restaurants_parent_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurants_parent_id ON restaurants USING btree (parent_id);


--
-- Name: restaurants_slug; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurants_slug ON restaurants USING btree (slug);


--
-- Name: restaurants_state_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurants_state_id ON restaurants USING btree (state_id);


--
-- Name: restaurants_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX restaurants_user_id ON restaurants USING btree (user_id);


--
-- Name: settings_setting_category_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX settings_setting_category_id ON settings USING btree (setting_category_id);


--
-- Name: states_country_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX states_country_id ON states USING btree (country_id);


--
-- Name: sudopay_payment_gateways_sudopay_gateway_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sudopay_payment_gateways_sudopay_gateway_id ON sudopay_payment_gateways USING btree (sudopay_gateway_id);


--
-- Name: sudopay_payment_gateways_sudopay_payment_group_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sudopay_payment_gateways_sudopay_payment_group_id ON sudopay_payment_gateways USING btree (sudopay_payment_group_id);


--
-- Name: sudopay_payment_gateways_users_sudopay_payment_gateway_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sudopay_payment_gateways_users_sudopay_payment_gateway_id ON sudopay_payment_gateways_users USING btree (sudopay_payment_gateway_id);


--
-- Name: sudopay_payment_gateways_users_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sudopay_payment_gateways_users_user_id ON sudopay_payment_gateways_users USING btree (user_id);


--
-- Name: sudopay_payment_groups_sudopay_group_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sudopay_payment_groups_sudopay_group_id ON sudopay_payment_groups USING btree (sudopay_group_id);


--
-- Name: sudopay_transaction_logs_class; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sudopay_transaction_logs_class ON sudopay_transaction_logs USING btree (class);


--
-- Name: sudopay_transaction_logs_foreign_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sudopay_transaction_logs_foreign_id ON sudopay_transaction_logs USING btree (foreign_id);


--
-- Name: sudopay_transaction_logs_gateway_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sudopay_transaction_logs_gateway_id ON sudopay_transaction_logs USING btree (gateway_id);


--
-- Name: sudopay_transaction_logs_merchant_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sudopay_transaction_logs_merchant_id ON sudopay_transaction_logs USING btree (merchant_id);


--
-- Name: transactions_class; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transactions_class ON transactions USING btree (class);


--
-- Name: transactions_foreign_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transactions_foreign_id ON transactions USING btree (foreign_id);


--
-- Name: transactions_other_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transactions_other_user_id ON transactions USING btree (other_user_id);


--
-- Name: transactions_payment_gateway_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transactions_payment_gateway_id ON transactions USING btree (payment_gateway_id);


--
-- Name: transactions_transaction_type_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transactions_transaction_type_id ON transactions USING btree (transaction_type_id);


--
-- Name: transactions_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX transactions_user_id ON transactions USING btree (user_id);


--
-- Name: user_addresses_city_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_addresses_city_id ON user_addresses USING btree (city_id);


--
-- Name: user_addresses_country_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_addresses_country_id ON user_addresses USING btree (country_id);


--
-- Name: user_addresses_state_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_addresses_state_id ON user_addresses USING btree (state_id);


--
-- Name: user_addresses_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_addresses_user_id ON user_addresses USING btree (user_id);


--
-- Name: user_cash_withdrawals_money_transfer_account_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_cash_withdrawals_money_transfer_account_id ON user_cash_withdrawals USING btree (money_transfer_account_id);


--
-- Name: user_cash_withdrawals_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_cash_withdrawals_user_id ON user_cash_withdrawals USING btree (user_id);


--
-- Name: user_tokens_oauth_client_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_tokens_oauth_client_id ON user_tokens USING btree (oauth_client_id);


--
-- Name: user_tokens_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_tokens_user_id ON user_tokens USING btree (user_id);


--
-- Name: users_city_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_city_id ON users USING btree (city_id);


--
-- Name: users_country_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_country_id ON users USING btree (country_id);


--
-- Name: users_email; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_email ON users USING btree (email);


--
-- Name: users_gender_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_gender_id ON users USING btree (gender_id);


--
-- Name: users_last_login_ip_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_last_login_ip_id ON users USING btree (last_login_ip_id);


--
-- Name: users_provider_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_provider_id ON users USING btree (provider_id);


--
-- Name: users_role_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_role_id ON users USING btree (role_id);


--
-- Name: users_state_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_state_id ON users USING btree (state_id);


--
-- Name: users_username; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_username ON users USING btree (username);


--
-- Name: wallet_transaction_logs_class; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX wallet_transaction_logs_class ON wallet_transaction_logs USING btree (class);


--
-- Name: wallet_transaction_logs_foreign_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX wallet_transaction_logs_foreign_id ON wallet_transaction_logs USING btree (foreign_id);


--
-- Name: wallets_payment_gateway_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX wallets_payment_gateway_id ON wallets USING btree (payment_gateway_id);


--
-- Name: wallets_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX wallets_user_id ON wallets USING btree (user_id);


--
-- Name: cart_addons_cart_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cart_addons
    ADD CONSTRAINT cart_addons_cart_id_fkey FOREIGN KEY (cart_id) REFERENCES carts(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: cart_addons_restaurant_addon_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cart_addons
    ADD CONSTRAINT cart_addons_restaurant_addon_id_fkey FOREIGN KEY (restaurant_addon_id) REFERENCES restaurant_addons(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: cart_addons_restaurant_addon_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cart_addons
    ADD CONSTRAINT cart_addons_restaurant_addon_item_id_fkey FOREIGN KEY (restaurant_addon_item_id) REFERENCES restaurant_addon_items(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: cart_addons_restaurant_menu_addon_price_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cart_addons
    ADD CONSTRAINT cart_addons_restaurant_menu_addon_price_id_fkey FOREIGN KEY (restaurant_menu_addon_price_id) REFERENCES restaurant_menu_addon_prices(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: carts_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY carts
    ADD CONSTRAINT carts_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: carts_restaurant_menu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY carts
    ADD CONSTRAINT carts_restaurant_menu_id_fkey FOREIGN KEY (restaurant_menu_id) REFERENCES restaurant_menus(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: carts_restaurant_menu_price_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY carts
    ADD CONSTRAINT carts_restaurant_menu_price_id_fkey FOREIGN KEY (restaurant_menu_price_id) REFERENCES restaurant_menu_prices(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: carts_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY carts
    ADD CONSTRAINT carts_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: cities_country_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cities
    ADD CONSTRAINT cities_country_id_fkey FOREIGN KEY (country_id) REFERENCES countries(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: cities_state_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cities
    ADD CONSTRAINT cities_state_id_fkey FOREIGN KEY (state_id) REFERENCES states(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: contacts_ip_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY contacts
    ADD CONSTRAINT contacts_ip_id_fkey FOREIGN KEY (ip_id) REFERENCES ips(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: coupons_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY coupons
    ADD CONSTRAINT coupons_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE;


--
-- Name: coupons_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY coupons
    ADD CONSTRAINT coupons_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: device_details_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY device_details
    ADD CONSTRAINT device_details_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: ips_city_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ips
    ADD CONSTRAINT ips_city_id_fkey FOREIGN KEY (city_id) REFERENCES cities(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: ips_country_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ips
    ADD CONSTRAINT ips_country_id_fkey FOREIGN KEY (country_id) REFERENCES countries(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: ips_state_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY ips
    ADD CONSTRAINT ips_state_id_fkey FOREIGN KEY (state_id) REFERENCES states(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: money_transfer_accounts_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY money_transfer_accounts
    ADD CONSTRAINT money_transfer_accounts_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: order_item_addons_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_item_addons
    ADD CONSTRAINT order_item_addons_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: order_item_addons_order_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_item_addons
    ADD CONSTRAINT order_item_addons_order_item_id_fkey FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: order_item_addons_restaurant_addon_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_item_addons
    ADD CONSTRAINT order_item_addons_restaurant_addon_id_fkey FOREIGN KEY (restaurant_addon_id) REFERENCES restaurant_addons(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: order_item_addons_restaurant_menu_addon_price_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_item_addons
    ADD CONSTRAINT order_item_addons_restaurant_menu_addon_price_id_fkey FOREIGN KEY (restaurant_menu_addon_price_id) REFERENCES restaurant_menu_addon_prices(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: order_items_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: order_items_restaurant_menu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_restaurant_menu_id_fkey FOREIGN KEY (restaurant_menu_id) REFERENCES restaurant_menus(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: order_items_restaurant_menu_price_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY order_items
    ADD CONSTRAINT order_items_restaurant_menu_price_id_fkey FOREIGN KEY (restaurant_menu_price_id) REFERENCES restaurant_menu_prices(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: orders_city_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_city_id_fkey FOREIGN KEY (city_id) REFERENCES cities(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: orders_country_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_country_id_fkey FOREIGN KEY (country_id) REFERENCES countries(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: orders_order_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_order_status_id_fkey FOREIGN KEY (order_status_id) REFERENCES order_statuses(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: orders_payment_gateway_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_payment_gateway_id_fkey FOREIGN KEY (payment_gateway_id) REFERENCES payment_gateways(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: orders_restaurant_branch_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_restaurant_branch_id_fkey FOREIGN KEY (restaurant_branch_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: orders_restaurant_delivery_person_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_restaurant_delivery_person_id_fkey FOREIGN KEY (restaurant_delivery_person_id) REFERENCES restaurant_delivery_persons(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: orders_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: orders_state_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_state_id_fkey FOREIGN KEY (state_id) REFERENCES states(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: orders_user_address_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_user_address_id_fkey FOREIGN KEY (user_address_id) REFERENCES user_addresses(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: orders_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY orders
    ADD CONSTRAINT orders_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: payment_gateway_settings_payment_gateway_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY payment_gateway_settings
    ADD CONSTRAINT payment_gateway_settings_payment_gateway_id_fkey FOREIGN KEY (payment_gateway_id) REFERENCES payment_gateways(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: provider_users_provider_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY provider_users
    ADD CONSTRAINT provider_users_provider_id_fkey FOREIGN KEY (provider_id) REFERENCES providers(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: provider_users_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY provider_users
    ADD CONSTRAINT provider_users_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: push_notifications_user_device_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY push_notifications
    ADD CONSTRAINT push_notifications_user_device_id_fkey FOREIGN KEY (user_device_id) REFERENCES device_details(id) ON DELETE CASCADE;


--
-- Name: restaurant_addon_items_restaurant_addon_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_addon_items
    ADD CONSTRAINT restaurant_addon_items_restaurant_addon_id_fkey FOREIGN KEY (restaurant_addon_id) REFERENCES restaurant_addons(id) ON DELETE CASCADE;


--
-- Name: restaurant_addons_restaurant_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_addons
    ADD CONSTRAINT restaurant_addons_restaurant_category_id_fkey FOREIGN KEY (restaurant_category_id) REFERENCES restaurant_categories(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_addons_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_addons
    ADD CONSTRAINT restaurant_addons_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_categories_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_categories
    ADD CONSTRAINT restaurant_categories_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_cuisines_cuisine_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_cuisines
    ADD CONSTRAINT restaurant_cuisines_cuisine_id_fkey FOREIGN KEY (cuisine_id) REFERENCES cuisines(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_cuisines_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_cuisines
    ADD CONSTRAINT restaurant_cuisines_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_delivery_person_or_restaurant_delivery_person_i_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_delivery_person_orders
    ADD CONSTRAINT restaurant_delivery_person_or_restaurant_delivery_person_i_fkey FOREIGN KEY (restaurant_delivery_person_id) REFERENCES restaurant_delivery_persons(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_delivery_person_orders_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_delivery_person_orders
    ADD CONSTRAINT restaurant_delivery_person_orders_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_delivery_person_orders_restaurant_branch_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_delivery_person_orders
    ADD CONSTRAINT restaurant_delivery_person_orders_restaurant_branch_id_fkey FOREIGN KEY (restaurant_branch_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_delivery_person_orders_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_delivery_person_orders
    ADD CONSTRAINT restaurant_delivery_person_orders_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_delivery_person_orders_restaurant_supervisor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_delivery_person_orders
    ADD CONSTRAINT restaurant_delivery_person_orders_restaurant_supervisor_id_fkey FOREIGN KEY (restaurant_supervisor_id) REFERENCES restaurant_supervisors(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_delivery_persons_restaurant_branch_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_delivery_persons
    ADD CONSTRAINT restaurant_delivery_persons_restaurant_branch_id_fkey FOREIGN KEY (restaurant_branch_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_delivery_persons_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_delivery_persons
    ADD CONSTRAINT restaurant_delivery_persons_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_delivery_persons_restaurant_supervisor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_delivery_persons
    ADD CONSTRAINT restaurant_delivery_persons_restaurant_supervisor_id_fkey FOREIGN KEY (restaurant_supervisor_id) REFERENCES restaurant_supervisors(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_delivery_persons_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_delivery_persons
    ADD CONSTRAINT restaurant_delivery_persons_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: restaurant_menu_addon_prices_restaurant_addon_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_menu_addon_prices
    ADD CONSTRAINT restaurant_menu_addon_prices_restaurant_addon_id_fkey FOREIGN KEY (restaurant_addon_id) REFERENCES restaurant_addons(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_menu_addon_prices_restaurant_addon_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_menu_addon_prices
    ADD CONSTRAINT restaurant_menu_addon_prices_restaurant_addon_item_id_fkey FOREIGN KEY (restaurant_addon_item_id) REFERENCES restaurant_addon_items(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_menu_addon_prices_restaurant_menu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_menu_addon_prices
    ADD CONSTRAINT restaurant_menu_addon_prices_restaurant_menu_id_fkey FOREIGN KEY (restaurant_menu_id) REFERENCES restaurant_menus(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_menu_prices_restaurant_menu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_menu_prices
    ADD CONSTRAINT restaurant_menu_prices_restaurant_menu_id_fkey FOREIGN KEY (restaurant_menu_id) REFERENCES restaurant_menus(id) ON UPDATE SET NULL ON DELETE CASCADE;


--
-- Name: restaurant_menus_cuisine_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_menus
    ADD CONSTRAINT restaurant_menus_cuisine_id_fkey FOREIGN KEY (cuisine_id) REFERENCES cuisines(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_menus_restaurant_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_menus
    ADD CONSTRAINT restaurant_menus_restaurant_category_id_fkey FOREIGN KEY (restaurant_category_id) REFERENCES restaurant_categories(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_menus_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_menus
    ADD CONSTRAINT restaurant_menus_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_reviews_order_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_reviews
    ADD CONSTRAINT restaurant_reviews_order_id_fkey FOREIGN KEY (order_id) REFERENCES orders(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_reviews_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_reviews
    ADD CONSTRAINT restaurant_reviews_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_reviews_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_reviews
    ADD CONSTRAINT restaurant_reviews_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: restaurant_supervisors_restaurant_branch_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_supervisors
    ADD CONSTRAINT restaurant_supervisors_restaurant_branch_id_fkey FOREIGN KEY (restaurant_branch_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_supervisors_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_supervisors
    ADD CONSTRAINT restaurant_supervisors_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurant_supervisors_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_supervisors
    ADD CONSTRAINT restaurant_supervisors_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: restaurant_timings_restaurant_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurant_timings
    ADD CONSTRAINT restaurant_timings_restaurant_id_fkey FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurants_city_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurants
    ADD CONSTRAINT restaurants_city_id_fkey FOREIGN KEY (city_id) REFERENCES cities(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurants_country_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurants
    ADD CONSTRAINT restaurants_country_id_fkey FOREIGN KEY (country_id) REFERENCES countries(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurants_parent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurants
    ADD CONSTRAINT restaurants_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES restaurants(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurants_state_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurants
    ADD CONSTRAINT restaurants_state_id_fkey FOREIGN KEY (state_id) REFERENCES states(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: restaurants_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY restaurants
    ADD CONSTRAINT restaurants_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: states_country_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY states
    ADD CONSTRAINT states_country_id_fkey FOREIGN KEY (country_id) REFERENCES countries(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: user_tokens_oauth_client_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_tokens
    ADD CONSTRAINT user_tokens_oauth_client_id_fkey FOREIGN KEY (oauth_client_id) REFERENCES oauth_clients(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: user_tokens_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY user_tokens
    ADD CONSTRAINT user_tokens_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

