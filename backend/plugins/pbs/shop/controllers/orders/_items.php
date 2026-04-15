<div class="form-group">
    <table class="table data">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th class="text-right">Price</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->items as $item): ?>
                <tr>
                    <td>
                        <?= e($item->product->title ?? 'Product #' . $item->product_id) ?>
                    </td>
                    <td><?= $item->quantity ?></td>
                    <td class="text-right">$<?= number_format($item->price, 2) ?></td>
                    <td class="text-right">$<?= number_format($item->price * $item->quantity, 2) ?></td>
                </tr>
            <?php endforeach ?>
            <?php if ($model->items->isEmpty()): ?>
                <tr>
                    <td colspan="4" class="no-data">No items found for this order.</td>
                </tr>
            <?php endif ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th class="text-right">$<?= number_format($model->total, 2) ?></th>
            </tr>
        </tfoot>
    </table>
</div>
