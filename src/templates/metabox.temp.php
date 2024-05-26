<style>
    .olmec-stock-metabox {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .olmec-stock-metabox ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .olmec-stock-metabox li {
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: start;
        border-bottom: 1px solid #eee;
    }

    .olmec-stock-metabox li:last-child {
        border-bottom: none;
    }

    .olmec-stock-metabox .stock-name,
    .olmec-stock-metabox .stock-quantity,
    .olmec-stock-metabox .stock-status {
        flex: 1;
        font-weight: bold;
    }

    .olmec-stock-metabox .stock-quantity {
        margin-left: 10px;
        color: #555;
    }

    .olmec-stock-metabox .stock-status {
        margin-left: 10px;
        color: #666;
        font-style: italic;
    }

    .olmec-stock-metabox .no-stock {
        color: #777;
        font-style: italic;
    }

    .olmec-stock-metabox .low-stock {
        color: #660000;
        font-weight: bold;
    }
</style>

<div class="olmec-stock-metabox">
    <?php if (!empty($stocks)): ?>
        <ul>
            <?php foreach ($stocks as $stock): ?>
                <li>
                    <span class="stock-name <?php echo ($stock['quantity'] < 15) ? 'low-stock' : '' ?>">
                        <?php echo esc_html($stock['name']) ?>
                    </span>
                    <span class="stock-quantity <?php echo ($stock['quantity'] < 15) ? 'low-stock' : '' ?>">
                        <?php echo esc_html($stock['quantity']) ?>
                    </span>
                    <span class="stock-status <?php echo ($stock['quantity'] < 15) ? 'low-stock' : '' ?>">
                        <?php _e('in stock.', OLMEC_LOW_STOCK_TEXT_DOMAIN) ?>
                    </span>
                </li>
            <?php endforeach ?>
        </ul>
    <?php else: ?>
        <p class="no-stock"><?php _e('No stock information available.', OLMEC_LOW_STOCK_TEXT_DOMAIN) ?></p>
    <?php endif ?>
</div>