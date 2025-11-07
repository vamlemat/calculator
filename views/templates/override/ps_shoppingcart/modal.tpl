<div id="blockcart-modal" class="modal fade accessory-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload()">
                    <span aria-hidden="true">&times;</span>
                </button>
                <a href="{$cart_url}" class="btn btn-primary">
                    {l s='Continuar' d='Shop.Theme.Actions'}
                    <i class="material-icons">keyboard_arrow_right</i>
                </a>
                <h3 class="modal-title h6 text-xs-center" id="myModalLabel"><i class="material-icons">keyboard_arrow_right</i>{l s='Producto añadido satisfactoriamente al tu carrito' d='Shop.Theme.Checkout'}</h3>
            </div>
            <!-- <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="accesories-header">
                            <h2>Accesorios recomendados para tu selección</h2>
                        </div>

                        <div class="container">
                            {foreach from=$accesories item="accesory"}
                            <div class="row accessory-category mim-modal-product-extras">
                                <div class="mim-modal-product-extras-inner">
                                    <div class="mim-modal-product-extras-header">
                                        <figure class="col-xs-12 col-sm-2">
                                            <img src="{$accesory['category']['image']['small']['url']}" alt="{$accesory['category']['name']}">
                                        </figure>
                                        <div class="col-xs-7">
                                            <div>
                                                <h2>{$accesory['category']['name']}</h2>
                                            </div>
                                            <div>
                                                {$accesory['category']['description']}
                                            </div>
                                        </div>

                                        <div class="col-xs-3">
                                            <button type="button" class="btn" data-toggle="collapse" data-target="#category-{$accesory['category']['id']}">Ver todos</button>
                                        </div>
                                    </div>
                                    <div id="category-{$accesory['category']['id']}" class="collapse">
                                        {foreach from=$accesory['products'] item="aproduct"}
                                            {include file="catalog/_partials/miniatures/accesory.tpl" product=$aproduct}
                                        {/foreach}
                                    </div>
                                </div>
                            </div>
                            {/foreach}
                        </div>

                        <div class="accesories-footer">
                            <a type="button" class="btn btn-primary col-xs-12 col-sm-2" onclick="location.reload()" href="" data-dismiss="modal">
                                <i class="material-icons">
                                    keyboard_arrow_left
                                </i>
                                {l s='Seguir comprando'
                                d='Shop.Theme.Actions'}</a>
                            <a href="{$cart_url}" class="btn btn-primary col-xs-12 col-sm-2">
                                {l s='Continuar' d='Shop.Theme.Actions'}
                                <i class="material-icons">keyboard_arrow_right</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
<style media="screen">
.modal-body> :first-child{
    border: 0px;
}
.mim-modal-product-extras{
    border-bottom: 0px;
}
.mim-modal-product-extras .mim-modal-product-extras-inner{
    margin-bottom: 20px;
    border: solid 1px #ccc;
    border-radius: 5px;
}
.mim-modal-product-extras .mim-modal-product-extras-inner .mim-modal-product-extras-header{
    display: flex;
    align-items: center;
}
.mim-modal-product-extras .mim-modal-product-extras-inner .mim-modal-product-extras-header figure{
    margin: 0px;
    float: none;
}
.mim-modal-product-extras .mim-modal-product-extras-inner .collapse{
    padding: 20px;
    width: 100%;
    box-sizing: border-box;
}
.mim-modal-product-extras .mim-modal-product-extras-inner .collapse .accessory-item{
    box-sizing: border-box;
    margin: 0px;
    padding: 10px;
    border-bottom: 0px;
    border-top: solid 1px #eee;
    float: none;
}
.mim-modal-product-extras .mim-modal-product-extras-inner .collapse .accessory-item .col-md-text-left{
    font-size: 14px;
}
.mim-modal-product-extras .mim-modal-product-extras-inner .collapse .accessory-item .col-md-text-left h4{
    font-size: 18px;
    color: #121212;
}
</style>
    <script>
     function onAccessoryAreaInput(areaInput) {
         var accessoryId = areaInput.data('accessory-id');
         var accessoryPrice = parseFloat(areaInput.data('price'));
         var area = areaInput.val();
         var packageSize = areaInput.data('package-size')|| 1;
         var quantityInput = $("#accessory-qty-" + accessoryId);
         var unity = areaInput.data('unity');
         if (quantityInput) {
             var qtyVal = Math.ceil(area / packageSize);
             var totalPrice = (accessoryPrice * qtyVal).toFixed(2);
            //  console.log(qtyVal);
            //  console.log(accessoryPrice);
            //  console.log(totalPrice);
             quantityInput.val(Math.ceil(area / packageSize));
             $(areaInput).parent().parent().parent().find('.unt_pck').html(qtyVal+' '+unity+'<br><i>'+totalPrice+'€</i>');
         }
     }

     function onAccessoryQuantityInput(qtyInput) {
         var accessoryId = qtyInput.data('accessory-id');
         var quantity = qtyInput.val();
         var areaInput = $("#accessory-area-" + accessoryId);
         var packageSize = areaInput.data('package-size');
         console.log(qtyInput);
         if (areaInput) {
             var newValue = quantity * packageSize;
             areaInput.val(newValue.toFixed(2));
         }
     }
    </script>
</div>
