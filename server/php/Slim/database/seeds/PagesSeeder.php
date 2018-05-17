<?php

use Phinx\Seed\AbstractSeed;

class PagesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => 'For each visitor to our Web page our Web server automatically recognizes no information regarding the domain or e-mail address.

We collect the e-mail addresses of those who post messages to our bulletin board the e-mail addresses of those who communicate with us via e-mail the e-mail addresses of those who make postings to our chat areas user-specific information on what pages consumers access or visit information volunteered by the consumer such as survey information and/or site registrations name and address telephone number.

The information we collect is disclosed when legally required to do so at the request of governmental authorities conducting an investigation to verify or enforce compliance with the policies governing our Website and applicable laws or to protect against misuse or unauthorized use of our Website to a successor entity in connection with a corporate merger consolidation sale of assets or other corporate change respecting the Website.

With respect to cookies. We use cookies to record session information such as items that consumers add to their shopping cart.

If you do not want to receive e-mail from us in the future please let us know by sending us e-mail at the above address.

Persons who supply us with their telephone numbers on-line will only receive telephone contact from us with information regarding orders they have placed on-line. Please provide us with your name and phone number. We will be sure your name is removed from the list we share with other organizations.

With respect to Ad Servers. We do not partner with or have special relationships with any ad server companies.

From time to time we may use customer information for new unanticipated uses not previously disclosed in our privacy notice. If our information practices change at some time in the future we will post the policy changes to our Web site to notify you of these changes and we will use for these new purposes only data collected from the time of the policy change forward. If you are concerned about how your information is used you should check back at our Web site periodically.

Upon request we provide site visitors with access to transaction information (e.g. dates on which customers made purchases amounts and types of purchases) that we maintain about them.

Upon request we offer visitors the ability to have inaccuracies corrected in contact information transaction information.

With respect to security. When we transfer and receive certain types of sensitive information such as financial or health information we redirect visitors to a secure server and will notify visitors through a pop-up screen on our site.

If you feel that this site is not following its stated information policy you may contact us at the above addresses or phone number.',
                'meta_keywords' => 'privacy',
                'meta_description' => 'privacy,policy',
                'is_active' => 1,
            ],  
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'content' => '<h1>Web Site Terms and Conditions of Use </h1><p>

1. Terms
By accessing this web site you are agreeing to be bound by these web site Terms and Conditions of Use all applicable laws and regulations and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms you are prohibited from using or accessing this site. The materials contained in this web site are protected by applicable copyright and trade mark law.&nbsp;</p><p>&nbsp;2. Use License

    Permission is granted to temporarily download one copy of the materials (information or software) on crowdfunding web site for personal non-commercial transitory viewing only. This is the grant of a license not a transfer of title and under this license you may not:
        modify or copy the materials;
        use the materials for any commercial purpose or for any public display (commercial or non-commercial);
        attempt to decompile or reverse engineer any software contained on crowdfunding web site;
        remove any copyright or other proprietary notations from the materials; or
        transfer the materials to another person or mirror the materials on any other server.
    This license shall automatically terminate if you violate any of these restrictions and may be terminated by crowdfunding at any time. Upon terminating your viewing of these materials or upon the termination of this license you must destroy any downloaded materials in your possession whether in electronic or printed format.</p><p>3. Disclaimer
The materials on crowdfunding web site are provided as is. crowdfunding makes no warranties expressed or implied and hereby disclaims and negates all other warranties including without limitation implied warranties or conditions of merchantability fitness for a particular purpose or non-infringement of intellectual property or other violation of rights. Further crowdfunding does not warrant or make any representations concerning the accuracy likely results or reliability of the use of the materials on its Internet web site or otherwise relating to such materials or on any sites linked to this site.</p><p>4. Limitations
In no event shall crowdfunding or its suppliers be liable for any damages (including without limitation damages for loss of data or profit or due to business interruption) arising out of the use or inability to use the materials on crowdfunding Internet site even if crowdfunding or a crowdfunding authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties or limitations of liability for consequential or incidental damages these limitations may not apply to you.</p><p>5. Revisions and Errata
The materials appearing on crowdfunding web site could include technical typographical or photographic errors. crowdfunding does not warrant that any of the materials on its web site are accurate complete or current. crowdfunding may make changes to the materials contained on its web site at any time without notice. crowdfunding does not however make any commitment to update the materials.</p><p>6. Links
crowdfunding has not reviewed all of the sites linked to its Internet web site and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by crowdfunding of the site. Use of any such linked web site is at the users own risk.</p><p>7. Site Terms of Use Modifications
crowdfunding may revise these terms of use for its web site at any time without notice. By using this web site you are agreeing to be bound by the then current version of these Terms and Conditions of Use.</p>',
                'meta_keywords' => 'terms',
                'meta_description' => 'terms',
                'is_active' => 1,
            ], 
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'title' => 'Acceptable Use Policy',
                'slug' => 'aup',
                'content' => 'You are independently responsible for complying with all applicable laws in all of your actions related to your use of PayPal’s services, regardless of the purpose of the use. In addition, you must adhere to the terms of this Acceptable Use Policy.

<h3> Prohibited Activities</h3>

You may not use the PayPal service for activities that:

violate any law, statute, ordinance or regulation
relate to sales of (a) narcotics, steroids, certain controlled substances or other products that present a risk to consumer safety, (b) drug paraphernalia, (c) items that encourage, promote, facilitate or instruct others to engage in illegal activity, (d) items that promote hate, violence, racial intolerance, or the financial exploitation of a crime, (e) items that are considered obscene, (f) items that infringe or violate any copyright, trademark, right of publicity or privacy or any other proprietary right under the laws of any jurisdiction, (g) certain sexually oriented materials or services, (h) ammunition, firearms, or certain firearm parts or accessories, or (i) certain weapons or knives regulated under applicable law
relate to transactions that (a) show the personal information of third parties in violation of applicable law, (b) support pyramid or ponzi schemes, matrix programs, other “get rich quick” schemes or certain multi-level marketing programs, (c) are associated with purchases of real property, annuities or lottery contracts, lay-away systems, off-shore banking or transactions to finance or refinance debts funded by a credit card, (d) are for the sale of certain items before the seller has control or possession of the item, (e) are by payment processors to collect payments on behalf of merchants, (f) are associated with the following Money Service Business Activities: the sale of traveler’s cheques or money orders, currency exchanges or cheque cashing, or (g) provide certain credit repair or debt settlement services
involve the sales of products or services identified by government agencies to have a high likelihood of being fraudulent
violate applicable laws or industry regulations regarding the sale of (a) tobacco products, or (b) prescription drugs and devices
involve gambling, gaming and/or any other activity with an entry fee and a prize, including, but not limited to casino games, sports betting, horse or greyhound racing, lottery tickets, other ventures that facilitate gambling, games of skill (whether or not it is legally defined as a lottery) and sweepstakes unless the operator has obtained prior approval from PayPal and the operator and customers are located exclusively in jurisdictions where such activities are permitted by law.
',
                'meta_keywords' => 'policy',
                'meta_description' => 'policy',
                'is_active' => 1,
            ]                                                         
        ];

        $pages = $this->table('pages');
        $pages->insert($data)
              ->save();
    }
}
