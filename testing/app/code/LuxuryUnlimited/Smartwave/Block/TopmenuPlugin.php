<?php
namespace LuxuryUnlimited\Smartwave\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Helper\Category;
use Smartwave\Megamenu\Helper\Data;
use Magento\Catalog\Model\Indexer\Category\Flat\State;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Theme\Block\Html\Topmenu as MagentoTopmenu;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Cms\Model\BlockFactory;
use Magento\Store\Model\StoreManagerInterface;
use Smartwave\Porto\Helper\Data as PortoHelper;

class TopmenuPlugin extends \Smartwave\Megamenu\Block\Topmenu
{

    protected $portoHelper;

    /**
     * Constructor
     *
     * @param Template\Context $context
     * @param Category $categoryHelper
     * @param Data $helper
     * @param State $categoryFlatState
     * @param CategoryFactory $categoryFactory
     * @param MagentoTopmenu $topMenu
     * @param FilterProvider $filterProvider
     * @param BlockFactory $blockFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Template\Context $context,
        Category $categoryHelper,
        Data $helper,
        State $categoryFlatState,
        CategoryFactory $categoryFactory,
        MagentoTopmenu $topMenu,
        FilterProvider $filterProvider,
        BlockFactory $blockFactory,
        StoreManagerInterface $storeManager,
        PortoHelper $portoHelper
    ) {
        $this->portoHelper = $portoHelper;
        $this->helper = $helper;
        parent::__construct($context, $categoryHelper, $helper, $categoryFlatState, $categoryFactory, $topMenu, $filterProvider, $blockFactory, $storeManager);
    }

    public function getMenuHelper()
    {
        return $this->helper;
    }

    public function getPortoHelper()
    {
        return $this->portoHelper;
    }
}
