<?php
namespace Affilicious\Shop\Infrastructure\Factory\In_Memory;

use Affilicious\Common\Domain\Model\Key;
use Affilicious\Common\Domain\Model\Name;
use Affilicious\Common\Domain\Model\Title;
use Affilicious\Product\Infrastructure\Repository\Carbon\Carbon_Product_Repository;
use Affilicious\Shop\Domain\Model\Affiliate_Id;
use Affilicious\Shop\Domain\Model\Affiliate_Link;
use Affilicious\Shop\Domain\Model\Currency;
use Affilicious\Shop\Domain\Model\Price;
use Affilicious\Shop\Domain\Model\Shop;
use Affilicious\Shop\Domain\Model\Shop_Factory_Interface;
use Affilicious\Shop\Domain\Model\Shop_Template_Id;
use Affilicious\Shop\Domain\Model\Shop_Template_Repository_Interface;

if (!defined('ABSPATH')) {
    exit('Not allowed to access pages directly.');
}

class In_Memory_Shop_Factory implements Shop_Factory_Interface
{
    /**
     * @var Shop_Template_Repository_Interface
     */
    private $shop_template_repository;

    /**
     * @since 0.6
     * @param Shop_Template_Repository_Interface $shop_template_repository
     */
    public function __construct(Shop_Template_Repository_Interface $shop_template_repository)
    {
        $this->shop_template_repository = $shop_template_repository;
    }

    /**
     * @inheritdoc
     * @since 0.6
     */
    public function create(Title $title, Name $name, Key $key, Affiliate_Link $affiliate_link, Currency $currency)
    {
        $shop = new Shop($title, $name, $key, $affiliate_link, $currency);

        return $shop;
    }

    /**
     * @inheritdoc
     * @since 0.6
     */
    public function create_from_template_id_and_data(Shop_Template_Id $shop_template_id, $data)
    {
        $shop_template = $this->shop_template_repository->find_by_id($shop_template_id);
        if($shop_template === null || !is_array($data)) {
            return null;
        }

        $affiliate_link = !empty($data[Carbon_Product_Repository::SHOP_AFFILIATE_LINK]) ? $data[Carbon_Product_Repository::SHOP_AFFILIATE_LINK] : null;
        $affiliate_id = !empty($data[Carbon_Product_Repository::SHOP_AFFILIATE_ID]) ? $data[Carbon_Product_Repository::SHOP_AFFILIATE_ID] : null;
        $price = !empty($data[Carbon_Product_Repository::SHOP_PRICE]) ? floatval($data[Carbon_Product_Repository::SHOP_PRICE]) : null;
        $old_price = !empty($data[Carbon_Product_Repository::SHOP_OLD_PRICE]) ? floatval($data[Carbon_Product_Repository::SHOP_OLD_PRICE]) : null;
        $currency = !empty($data[Carbon_Product_Repository::SHOP_CURRENCY]) ? $data[Carbon_Product_Repository::SHOP_CURRENCY] : null;

        if(empty($affiliate_link) || empty($currency)) {
            return null;
        }

        $shop = $this->create(
            $shop_template->get_title(),
            $shop_template->get_name(),
            $shop_template->get_key(),
            new Affiliate_Link($affiliate_link),
            new Currency($currency)
        );

        $shop->set_template_id($shop_template_id);

        if($shop_template->has_thumbnail()) {
            $shop->set_thumbnail($shop_template->get_thumbnail());
        }

        if(!empty($affiliate_id)) {
            $shop->set_affiliate_id(new Affiliate_Id($affiliate_id));
        }

        if(!empty($price)) {
            $shop->set_price(new Price($price, $shop->get_currency()));
        }

        if(!empty($old_price)) {
            $shop->set_old_price(new Price($old_price, $shop->get_currency()));
        }

        return $shop;
    }
}