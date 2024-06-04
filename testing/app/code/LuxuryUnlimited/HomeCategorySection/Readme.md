## Home Page Category Section

This module is used to Add the categories to the Home page, as section #1 and section #2.
There is a store configuration to enable and disable the entire section under Luxury Unlimited tab.
If it's enabled, it will filter the categories by 'Display in section #1' or 'Display in section #2'.attribute in category.
The limit of categories (count) can be configured under store configuration. If the limit is not set, All the included categories will be displayed.
To get this block in the home page, It should be included in the home page cms with
{{block class="LuxuryUnlimited\HomeCategorySection\Block\SectionOne" template="LuxuryUnlimited_HomeCategorySection::category_section_one.phtml"}}
The view file will have category name, image and url.
If the Image is not set to the category, Placeholder thumbnail will be returned. In store Configuration, placeholder thumbnail image should be set under catalog tab.
