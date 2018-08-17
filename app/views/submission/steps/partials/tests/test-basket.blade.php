<script type="text/javascript">
    function sticky_relocate() {
        var window_top = $(window).scrollTop();
        var div_top = $('#sticky-anchor').offset().top;
        if (window_top > div_top) {
            $('#sticky').addClass('stick');
        } else {
            $('#sticky').removeClass('stick');
        }
    }

    $(function () {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });
</script>
<div id="sticky-anchor"></div>
<div id="sticky" class="basket">
    <h2>Your basket</h2>

    <p id="NoTestsInBasket" @if(count($basketProducts))style="display: none"@endif>No tests in basket</p>

    <div @if(!count($basketProducts))style="display:none"@endif>

        <table id="basketContainer">

            <tr>
                <td>Total items</td>
                <td>{{{ $basket->getTotalItems() }}}</td>
            </tr>

            <tr>
                <td>
                    Total cost<br/>
                    (ex VAT)
                </td>
                <td>&pound; {{{ $basket->getTotalWithoutVat() }}}</td>
            </tr>
            <tr>
                <td colspan="2">Prices are indicative only - discounts for volume testing may be applied.</td>
            </tr>

        </table>

        {{ Form::submit('View basket',['name'=>'viewbasket','class'=>'button']) }}
        <div class="clear"></div>
    </div>

</div>




