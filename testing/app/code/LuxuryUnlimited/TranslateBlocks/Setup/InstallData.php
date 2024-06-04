<?php
/**
 * LuxuryUnlimited_TranslateBlocks
 *
 * @copyright   Copyright (c) 2023
 */

namespace LuxuryUnlimited\TranslateBlocks\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    private $_blockFactory;

    /**
     * @var Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfigInterface;

    /**
     * InstallData constructor
     *
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     */
    public function __construct(
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    )
    {
        $this->_blockFactory = $blockFactory;
        $this->scopeConfigInterface = $scopeConfigInterface;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $baseUrl = $this->scopeConfigInterface->getValue("web/unsecure/base_url");
        $sololuxury = strpos($baseUrl, "sololuxury");
        if ($sololuxury !== false) {
            $arrCmsBlocks = ['porto_custom_block_for_header','footer_links'];
            if (count($arrCmsBlocks)) {
                foreach ($arrCmsBlocks as $blockIdentifier) {
                    $cmsBlock = $this->_blockFactory->create()->load($blockIdentifier, 'identifier');
                    if ($cmsBlock) {
                        // Create CMS Block
                        if ($blockIdentifier == 'footer_links') {
                            $content = '
                    <div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'About US\'}}&lt;/p&gt;
                    &lt;ul class="footer-links"&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'about-us\'}}"&gt;{{trans \'About Us\'}}&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'returns-and-refunds\'}}"&gt;{{trans \'Returns\'}} &amp; Refunds&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'faqs\'}}"&gt;{{trans \'FAQ\'}}&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'app\'}}"&gt;{{trans \'APP\'}}&lt;/a&gt;&lt;/li&gt;
                    &lt;/ul&gt;</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'company\'}}&lt;/p&gt;
                    &lt;ul class="footer-links"&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'contact-us\'}}"&gt;{{trans \'Contact Us\'}}&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'secure-shopping\'}}"&gt;{{trans \'Secure Shopping\'}}&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'catalogsearch/advanced\'}}"&gt;{{trans \'Advancess Search\'}}&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'testimonial\'}}"&gt;{{trans \'Testimonials\'}}&lt;/a&gt;&lt;/li&gt;
                    &lt;/ul&gt;</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Contact\'}}&lt;/p&gt; 
                    &lt;ul class="footer-links"&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'privacy-policy\'}}"&gt;{{trans \'Privacy Policy\'}}&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'terms-and-conditions\'}}"&gt;{{trans \'Terms and Conditions\'}}&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'shipping-info\'}}"&gt;{{trans \'Shipping\'}}&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'track-your-order\'}}"&gt;{{trans \'Track Your Order\'}}&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'track-your-ticket-by-email\'}}"&gt;{{trans \'Track Your Ticket By Email\'}}&lt;/a&gt;&lt;/li&gt;
                    &lt;/ul&gt;</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Social\'}}&lt;/p&gt;
                    &lt;ul class="footer-links"&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'hope\'}}"&gt;{{trans \'Hope\'}}&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'affiliate-program\'}}"&gt;{{trans \'Affiliate Program\'}}m&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'influencer-registration\'}}"&gt;{{trans \'Influencer Registration\'}}&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="{{store url=\'terms-and-conditions\'}}"&gt;{{trans \'Exchange and Returns\'}}&lt;/a&gt;&lt;/li&gt;
                    &lt;/ul&gt;</div>
                    ';
                            $cmsBlock->setContent($content)->save();
                        }
                    }
                }
            }
        }

        $brandLabel = strpos($baseUrl, "brand");
        if ($brandLabel !== false) {
            $arrCmsBlocks = [
                'footer_links', 'header_panel_message', 'sidebar-static-block', 'swarming_credits_info', 'amp_footer_links',
                'phpro_cookie_consent_privacy_policy_content', 'phpro_cookie_consent_cookie_policy_content',
                'smartproducttabs_sizechart_woman', 'weltpixel_footer_v4', 'fullpagescroll_home-page-v6_section_3',
                'fullpagescroll_home-page-v6_section_2', 'fullpagescroll_home-page-v6_section_1', 'weltpixel_contact_page',
                'weltpixel_footer_v2', 'weltpixel_footer_v1', 'weltpixel_pre-footer', 'weltpixel_newsletter', 'login-data',
                'giftcard-block', 'eco-friendly-block', 'performance-fabrics-block', 'home-page-block', 'new-block',
                'sale-block', 'gear-block', 'men-block', 'training-block', 'women-block', 'women-left-menu-block',
                'men-left-menu-block', 'gear-left-menu-block', 'sale-left-menu-block', 'contact-us-info',
                'footer_links_block'
            ];
            if (count($arrCmsBlocks)) {
                foreach ($arrCmsBlocks as $blockIdentifier) {
                    $cmsBlock = $this->_blockFactory->create()->load($blockIdentifier, 'identifier');
                    if ($cmsBlock) {
                        // Create CMS Block
                        if ($blockIdentifier == 'footer_links') {
                            $content = '<div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;
    &lt;img src="{{media url=wysiwyg/footer-logo.png}}" alt="{{trans \'brands label\'}}" /&gt;
&lt;/p&gt;
&lt;ul class="social-sec"&gt;
    &lt;li class="instagram"&gt;&lt;a target="_blank" href="#"&gt;&lt;i class="icon-instagram"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li class="twitter"&gt;&lt;a target="_blank" href="#"&gt;&lt;i class="icon-twitter"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li class="facebook"&gt;&lt;a target="_blank" href="#"&gt;&lt;i class="icon-facebook"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Company\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'about-us\'}}"&gt;{{trans \'About Us\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'contact-us\'}}"&gt;{{trans \'Contact Us\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'careers\'}}"&gt;{{trans \'Careers\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'shipping\'}}"&gt;{{trans \'Shipping\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'secure-shopping\'}}"&gt;{{trans \'Secure Shopping\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Usefull Links\' }}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'#\'}}"&gt;{{trans \'Designer\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'track-your-order\'}}"&gt;{{trans \'Track Your Order\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'track-your-ticket-by-email\'}}"&gt;{{trans \'Track Ticket\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'#\'}}"&gt;{{trans \'Gift Vouchers\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'refer-friend\'}}"&gt;{{trans \'Refer A Friends\'}} &amp; {{trans \'Earn Discounts\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Social\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'change-the-world\'}}"&gt;{{trans \'Our Social Initiative\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'affiliate-program\'}}"&gt;{{trans \'Affiliate Program\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'influencer-registration\'}}"&gt;{{trans \'Inflluerncer Registration\' }}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'#\'}}"&gt;{{trans \'Donation\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Legal\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'faqs\'}}"&gt;{{trans \'FAQ\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'returns-and-refunds\'}}"&gt;{{trans \'Returns and Refunds\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'privacy-policy\'}}"&gt;{{trans \'Privacy Policy\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'terms-and-conditions\'}}"&gt;{{trans \'Terms and Conditions\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</div>';
                            $cmsBlock->setContent($content)->save();
                        }
                        /**
                         * @Todo similary we need to get the content for other blocks from admin and then add  {{  trans ""}} for
                         * all the static text present in the block and update the and also update the links for each href
                         * if you are not sure about href link plz ask in the ticket
                         */

                    }
                }
            }
        }
        $avoirChic = strpos($baseUrl, "avoir");
        if ($avoirChic !== false) {
            $arrCmsBlocks = [
                'footer_links', 'header_panel_message', 'sidebar-static-block', 'swarming_credits_info', 'amp_footer_links',
                'phpro_cookie_consent_privacy_policy_content', 'phpro_cookie_consent_cookie_policy_content',
                'smartproducttabs_sizechart_woman', 'weltpixel_footer_v4', 'fullpagescroll_home-page-v6_section_3',
                'fullpagescroll_home-page-v6_section_2', 'fullpagescroll_home-page-v6_section_1', 'weltpixel_contact_page',
                'weltpixel_footer_v2', 'weltpixel_footer_v1', 'weltpixel_pre-footer', 'weltpixel_newsletter', 'login-data',
                'giftcard-block', 'eco-friendly-block', 'performance-fabrics-block', 'home-page-block', 'new-block',
                'sale-block', 'gear-block', 'men-block', 'training-block', 'women-block', 'women-left-menu-block',
                'men-left-menu-block', 'gear-left-menu-block', 'sale-left-menu-block', 'contact-us-info',
                'footer_links_block'
            ];
            if (count($arrCmsBlocks)) {
                foreach ($arrCmsBlocks as $blockIdentifier) {
                    $cmsBlock = $this->_blockFactory->create()->load($blockIdentifier, 'identifier');
                    if ($cmsBlock) {
                        // Create CMS Block
                        if ($blockIdentifier == 'footer_links') {
                            $content = '<div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">
&lt;p class="links-title"&gt;{{trans \'About\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'about-us\'}}"&gt;{{trans \'About Us\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'contact-us\'}}"&gt;{{trans \'Contact Us\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'refer-friend\'}}"&gt;{{trans \'Spread the word & Earn Discount\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'careers\'}}"&gt;{{trans \'Careers\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'app\'}}"&gt;{{trans \'App\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">
&lt;p class="links-title"&gt;{{trans \'Customer Policy\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'shipping\'}}"&gt;{{trans \'Shipping\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'privacy-policy\'}}"&gt;{{trans \'Privacy Policy\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'track-your-ticket-by-email\'}}"&gt;{{trans \'Track Ticket\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'terms-and-conditions\'}}"&gt;{{trans \'Terms and Conditions\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'faqs\'}}"&gt;{{trans \'FAQ\'}} &amp; {{trans \'Earn Discounts\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">
&lt;p class="links-title"&gt;{{trans \'Help\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'secure-shopping\'}}"&gt;{{trans \'Secure Shopping\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'improve-lives\'}}"&gt;{{trans \'Improve Lives\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'affiliate-program\'}}"&gt;{{trans \'Affiliate Registration\' }}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'influencer-registration\'}}"&gt;{{trans \'Influencer Registration\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'track-your-ticket-by-email\'}}"&gt;{{trans \'Track Your Ticket By Email\'}}&lt;/a&gt;&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Follow Us\'}}&lt;/p&gt;
&lt;ul class="footer-links footer-social-links"&gt;
    &lt;li class="instagram"&gt;&lt;a target="_blank" href="#"&gt;&lt;i class="icon-instagram"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li class="facebook"&gt;&lt;a target="_blank" href="#"&gt;&lt;i class="icon-facebook"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
&lt;ul class="footer-links footer-social-other"&gt;
    &lt;li class="google-pay"&gt;&lt;a target="_blank" href="#"&gt;&lt;img src="{{media url=wysiwyg/google-play.png}}" alt="{{trans \'Google Play\'}}" /&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;li class="app-store"&gt;&lt;a target="_blank" href="#"&gt;&lt;img src="{{media url=wysiwyg/app-store.png}}" alt="{{trans \'App Store\'}}" /&gt;&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</div>';
                            $cmsBlock->setContent($content)->save();
                        }
                    }
                }
            }

        }
        $veralusso = strpos($baseUrl, "veralusso");
        if ($veralusso   !== false) {
            $arrCmsBlocks = [
                'footer_links', 'header_panel_message', 'sidebar-static-block', 'swarming_credits_info', 'amp_footer_links',
                'phpro_cookie_consent_privacy_policy_content', 'phpro_cookie_consent_cookie_policy_content',
                'smartproducttabs_sizechart_woman', 'weltpixel_footer_v4', 'fullpagescroll_home-page-v6_section_3',
                'fullpagescroll_home-page-v6_section_2', 'fullpagescroll_home-page-v6_section_1', 'weltpixel_contact_page',
                'weltpixel_footer_v2', 'weltpixel_footer_v1', 'weltpixel_pre-footer', 'weltpixel_newsletter', 'login-data',
                'giftcard-block', 'eco-friendly-block', 'performance-fabrics-block', 'home-page-block', 'new-block',
                'sale-block', 'gear-block', 'men-block', 'training-block', 'women-block', 'women-left-menu-block',
                'men-left-menu-block', 'gear-left-menu-block', 'sale-left-menu-block', 'contact-us-info',
                'footer_links_block'
            ];
            if (count($arrCmsBlocks)) {
                foreach ($arrCmsBlocks as $blockIdentifier) {
                    $cmsBlock = $this->_blockFactory->create()->load($blockIdentifier, 'identifier');
                    if ($cmsBlock) {
                        // Create CMS Block
                        if ($blockIdentifier == 'footer_links') {
                            $content = '<div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Company\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'shop-safely\'}}"&gt;{{trans \'Shop Safely\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'about-us\'}}"&gt;{{trans \'Veralusso\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'returns\'}}"&gt;{{trans \'Returns And Refunds\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'shipping\'}}"&gt;{{trans \'Shipping\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'secure-shopping\'}}"&gt;{{trans \'Secure Shopping\'}}&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'CONTACTS\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'contact-us\'}}"&gt;{{trans \'Contact Us\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'refer-friend\'}}"&gt;{{trans \'Refer A Friend\'}} &amp; {{trans \'Earn Discounts\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'search/term/popular\'}}"&gt;{{trans \'Terms Of Sale\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'change-the-world\'}}"&gt;{{trans \'Change The World\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'careers\'}}"&gt;{{trans \'Careers\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'app\'}}"&gt;{{trans \'APP\'}}&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'OTHERS\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'influencer-registration\'}}"&gt;{{trans \'Influencer Registration\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'faqs\'}}"&gt;{{trans \'FAQ\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'privacy-policy\'}}"&gt;{{trans \'Privacy Policy\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'affiliate-program\'}}"&gt;{{trans \'Affiliate Program\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'privacy-policy-cookie-restriction-mode\'}}"&gt;{{trans \'Product Information\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'catalogsearch/advanced\'}}"&gt;{{trans \'Advance Search\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'track-your-order\'}}"&gt;{{trans \'Track Your Order\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'track-your-ticket-by-email\'}}"&gt;{{trans \'Track Your Ticket By Email\'}}&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;</div>';
                            $cmsBlock->setContent($content)->save();
                        }
                    }
                }
            }

        }

        $suvandnav = strpos($baseUrl, "suvandnat");
        if ($suvandnav   !== false) {
            $arrCmsBlocks = [
                'footer_links', 'header_panel_message', 'sidebar-static-block', 'swarming_credits_info', 'amp_footer_links',
                'phpro_cookie_consent_privacy_policy_content', 'phpro_cookie_consent_cookie_policy_content',
                'smartproducttabs_sizechart_woman', 'weltpixel_footer_v4', 'fullpagescroll_home-page-v6_section_3',
                'fullpagescroll_home-page-v6_section_2', 'fullpagescroll_home-page-v6_section_1', 'weltpixel_contact_page',
                'weltpixel_footer_v2', 'weltpixel_footer_v1', 'weltpixel_pre-footer', 'weltpixel_newsletter', 'login-data',
                'giftcard-block', 'eco-friendly-block', 'performance-fabrics-block', 'home-page-block', 'new-block',
                'sale-block', 'gear-block', 'men-block', 'training-block', 'women-block', 'women-left-menu-block',
                'men-left-menu-block', 'gear-left-menu-block', 'sale-left-menu-block', 'contact-us-info',
                'footer_links_block'
            ];
            if (count($arrCmsBlocks)) {
                foreach ($arrCmsBlocks as $blockIdentifier) {
                    $cmsBlock = $this->_blockFactory->create()->load($blockIdentifier, 'identifier');
                    if ($cmsBlock) {
                        // Create CMS Block
                        if ($blockIdentifier == 'footer_links') {
                            $content = '<div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p&gt;
    &lt;a href="{{store direct_url=""}}"&gt;&lt;img src="{{media url=wysiwyg/footer-logo.png}}" alt="{{trans \'Suv &amp; Nat\'}}" /&gt;&lt;/a&gt;
&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;p&gt;Seamless shopping. Personalized recommendations. Excellent support.&lt;/p&gt;&lt;/li&gt;
&lt;/ul&gt;</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Company\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'about-us\'}}"&gt;{{trans \'About Us\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'contact-us\'}}"&gt;{{trans \'Contact Us\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'careers\'}}"&gt;{{trans \'Careers\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'shipping\'}}"&gt;{{trans \'Shipping\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'secure-shopping\'}}"&gt;{{trans \'Secure Shopping\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'catalogsearch/advanced\'}}"&gt;{{trans \'Advance search\'}}&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Useful Links\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'\'}}"&gt;{{trans \'Designer\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'track-your-order\'}}"&gt;{{trans \'Track Order\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'track-your-ticket-by-email\'}}"&gt;{{trans \'Track Ticket\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'\'}}"&gt;{{trans \'Gift Vouchers\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'refer-friend\'}}"&gt;{{trans \'Refer A Friends\'}} &amp; {{trans \'Earn Discounts\'}}&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Social\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'change-the-world\'}}"&gt;{{trans \'Our Social Initiative\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'affiliate-program\'}}"&gt;{{trans \'Affiliate program\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'influencer-registration\'}}"&gt;{{trans \'Influencer Registration\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'\'}}"&gt;{{trans \'Donation\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'app\'}}"&gt;{{trans \'App\'}}&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;</div><div class="custom-col" data-content-type="html" data-appearance="default" data-element="main">&lt;p class="links-title"&gt;{{trans \'Legal\'}}&lt;/p&gt;
&lt;ul class="footer-links"&gt;
    &lt;li&gt;&lt;a href="{{store url=\'faqs\'}}"&gt;{{trans \'FAQ\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'returns\'}}"&gt;{{trans \'Returns And Refunds\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'privacy-policy\'}}"&gt;{{trans \'Privacy Policy\'}}&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="{{store url=\'terms-and-conditions\'}}"&gt;{{trans \'Terms and Conditions\'}}&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;</div>';
                            $cmsBlock->setContent($content)->save();
                        }
                    }
                }
            }

        }



    }
}