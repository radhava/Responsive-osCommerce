<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

  class ht_product_title {
    var $code = 'ht_product_title';
    var $group = 'header_tags';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->title = MODULE_HEADER_TAGS_PRODUCT_TITLE_TITLE;
      $this->description = MODULE_HEADER_TAGS_PRODUCT_TITLE_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_PRODUCT_TITLE_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_PRODUCT_TITLE_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_PRODUCT_TITLE_STATUS == 'True');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $languages_id, $product_check;
      
      if (basename($PHP_SELF) == 'product_info.php') {
        if (isset($_GET['products_id'])) {
          if ($product_check['total'] > 0) {
            $product_info_query = tep_db_query("select pd.products_name, pd.products_seo_title from products p, products_description pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
            $product_info = tep_db_fetch_array($product_info_query);

            if ( tep_not_null($product_info['products_seo_title']) && (MODULE_HEADER_TAGS_PRODUCT_TITLE_SEO_TITLE_OVERRIDE == 'True') ) {
              $oscTemplate->setTitle($product_info['products_seo_title'] . MODULE_HEADER_TAGS_PRODUCT_SEO_SEPARATOR . $oscTemplate->getTitle());
            }
            else {
              $oscTemplate->setTitle($product_info['products_name'] . MODULE_HEADER_TAGS_PRODUCT_SEO_SEPARATOR . $oscTemplate->getTitle());
            }
          }
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_PRODUCT_TITLE_STATUS');
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Title Module', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_STATUS', 'True', 'Do you want to allow product titles to be added to the page title?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('SEO Title Override?', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SEO_TITLE_OVERRIDE', 'True', 'Do you want to allow product titles to be over-ridden by your SEO Titles (if set)?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('SEO Breadcrumb Override?', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SEO_BREADCRUMB_OVERRIDE', 'True', 'Do you want to allow product names in the breadcrumb to be over-ridden by your SEO Titles (if set)?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_PRODUCT_TITLE_STATUS', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SEO_TITLE_OVERRIDE', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SEO_BREADCRUMB_OVERRIDE', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SORT_ORDER');
    }
  }
  