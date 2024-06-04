## Api for home page

-   Api to get category sections
    Request END : http://{magento-root}/rest/{store-code}/V1/home/category-section/:section
    example end point : http://brand-labels.in/rest/gb-en/V1/home/category-section/1
    <response>
    <item>true</item>
    <item>
    <item>
    <entity_id>1899</entity_id>
    <attribute_set_id>3</attribute_set_id>
    <parent_id>2</parent_id>
    <created_at>2021-01-09 09:07:26</created_at>
    <updated_at>2023-10-04 07:50:35</updated_at>
    <path>1/2/1899</path>
    <position>2</position>
    <level>2</level>
    <children_count>69</children_count>
    <section_one_display>1</section_one_display>
    <description/>
    <meta_keywords/>
    <meta_description/>
    <virtual_rule>{"type":"Smile\\ElasticsuiteVirtualCategory\\Model\\Rule\\Condition\\Combine","attribute":null,"operator":null,"value":"1","is_value_processed":null,"aggregator":"all"}</virtual_rule>
    <name>Women</name>
    <image/>
    <meta_title/>
    <display_mode>PRODUCTS</display_mode>
    <custom_design/>
    <page_layout/>
    <url_key>women</url_key>
    <url_path>women</url_path>
    <thumbnail/>
    <amp_homepage_image/>
    <section_one_image>/media/catalog/category/config-translate-block-module.png</section_one_image>
    <section_two_image/>
    <is_active>1</is_active>
    <landing_page/>
    <is_anchor>1</is_anchor>
    <include_in_menu>1</include_in_menu>
    <custom_use_parent_settings>0</custom_use_parent_settings>
    <custom_apply_to_products>0</custom_apply_to_products>
    <use_name_in_product_search>1</use_name_in_product_search>
    <is_virtual_category>0</is_virtual_category>
    <virtual_category_root>2</virtual_category_root>
    <use_store_positions>0</use_store_positions>
    <is_displayed_in_autocomplete>1</is_displayed_in_autocomplete>
    <generate_root_category_subtree>0</generate_root_category_subtree>
    <section_two_display>1</section_two_display>
    <custom_design_from/>
    <custom_design_to/>
    </item>

Error Message
first section is not enabled
second section is not enabled
invalid section type or limit not defined
exception string

-   Api to get Brands
    Request END : http://{magento-root}/rest/{store-code}/V1/home/brand-section/:section  
    responce:
    -   [
        true,
        [
        {
        "brand_id": "3886",
        "attribute_id": "13280",
        "name": " barena",
        "description": "",
        "url_key": "-barena",
        "logo_path": "/s/e/selection_1314.png",
        "sort_order": "0",
        "is_active": "1",
        "is_featured": "0",
        "seo_title": "",
        "seo_desc": "",
        "seo_keyword": "",
        "updated_at": "2023-09-27 11:03:47",
        "created_at": "2022-05-18 17:00:05",
        "section_one_display": "1",
        "section_two_display": "1"
        }
        ]
        ]

Error Message
first section is not enabled
second section is not enabled
invalid section type
exception string

-   Api to get product sections
    http://brand-labels.in/rest/gb-en/V1/home/product-section/1
-   Api to get Most popular products
-   Api to get Sale products
-   Newsletter Api
