/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import $ from 'jquery';

$(document).ready(function () {
    $('.product-amount').on('keyup', function () {
        var amount = parseFloat($(this).val());
        var amountTVA = $('.product-amountTVA');
        var totalTVA = $('.product-totalTVA');
        var quantity = parseFloat($('.product-quantity').val());
        var inputamountTVA = (amount * quantity) * 0.2;
        amountTVA.val(inputamountTVA);
        var totalAmountTVA = (amount * quantity) + inputamountTVA;
        totalTVA.val(totalAmountTVA.toFixed(2));
    });

    $('.product-quantity').on('keyup', function () {
        var quantity = parseFloat($(this).val());
        var amountTVA = $('.product-amountTVA');
        var totalTVA = $('.product-totalTVA');
        var amount = parseFloat($('.product-amount').val());
        var inputamountTVA = (amount * quantity) * 0.2;
        amountTVA.val(inputamountTVA);
        var totalAmountTVA = (amount * quantity) + inputamountTVA;
        totalTVA.val(totalAmountTVA.toFixed(2));
    });
});
