## Home Page Product Section

This module is used to Add the Product list to the Home page, as section #1 section #2 and section #2.
There is a store configuration to enable and disable the entire section under Luxury Unlimited tab.
If it's enabled, it will display the products in the selected category.
The category can be selected from the category tree in the configuration.
The limit of Products (count) can be configured under store configuration. If the limit is not set, By default 4 products will be set.
To get this block in the home page, It should be included in the home page cms with
{{block class="LuxuryUnlimited\HomeProductSection\Block\SectionOne" template="LuxuryUnlimited_HomeProductSection::product_section_one.phtml"}}
The view file will have the selected category name, category Url as the caption.
The products in the selected category will be listed with image, name and link.
