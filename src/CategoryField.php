<?php

namespace Olmec\LowStock;

use Olmec\LowStock\Utils\LoadTemplate;

class CategoryField
{
    use LoadTemplate;

    public function addCategoryFields()
    {
        if (!is_admin()) {
            return;
        }
        $this->render('category-field');
    }

    public function editCategoryFields($term)
    {
        if (!is_admin()) {
            return;
        }
        $checked = get_term_meta($term->term_id, 'cat_low_stock_notification', true);
        $this->render('table-row-category-field', ['checked' => $checked]);
    }

    public function saveCategoryFields($termId)
    {
        $value = isset($_POST['cat_low_stock_notification']) ? 1 : 0;
        update_term_meta($termId, 'cat_low_stock_notification', $value);
    }
}
