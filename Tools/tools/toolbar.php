<?php

$version = '2.17';

global $vController, $action, $method, $_GET, $_POST, $time, $tree;

$tabOpcache = null;
$tabStatus = null;

try{
    $tabOpcache = opcache_get_configuration();
    $tabStatus = opcache_get_status();
}catch(Error $e){}

function searchReportCoverage()
{
    $path = 'Tests/reports/FullTest/index.html';

    if(file_exists($path)){
        return $path;
    }
}

$fileIndex = searchReportCoverage();
?>
<!--<link rel="stylesheet" href="<?php echo str_replace('public/', '',WEBROOT) . 'toolbar/bootstrap-toolbar.css'; ?>">-->
<style type="text/css">

    [tabindex="-1"]:focus:not(:focus-visible) {
        outline: 0 !important;
    }

    .h1, .h2, .h3, .h4, .h5, .h6 {
        margin-bottom: 0.5rem;
        font-weight: 500;
        line-height: 1.2;
    }

    .nm_h1 {
        font-size: 2.5rem;
    }

    .nm_h2 {
        font-size: 2rem;
    }

    .nm_h3 {
        font-size: 1.75rem;
    }

    .nm_h4 {
        font-size: 1.5rem;
    }

    .nm_h5 {
        font-size: 1.25rem;
    }

    .nm_h6 {
        font-size: 1rem;
    }

    .nm_lead {
        font-size: 1.25rem;
        font-weight: 300;
    }

    .nm_display-1 {
        font-size: 6rem;
        font-weight: 300;
        line-height: 1.2;
    }

    .nm_display-2 {
        font-size: 5.5rem;
        font-weight: 300;
        line-height: 1.2;
    }

    .nm_display-3 {
        font-size: 4.5rem;
        font-weight: 300;
        line-height: 1.2;
    }

    .nm_display-4 {
        font-size: 3.5rem;
        font-weight: 300;
        line-height: 1.2;
    }

    .nm_mark {
        padding: 0.2em;
        background-color: #fcf8e3;
    }

    .nm_list-unstyled {
        padding-left: 0;
        list-style: none;
    }

    .nm_list-inline {
        padding-left: 0;
        list-style: none;
    }

    .nm_list-inline-item {
        display: inline-block;
    }

    .nm_list-inline-item:not(:last-child) {
        margin-right: 0.5rem;
    }

    .nm_initialism {
        font-size: 90%;
        text-transform: uppercase;
    }

    .nm_blockquote {
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .nm_blockquote-footer {
        display: block;
        font-size: 80%;
        color: #6c757d;
    }

    .nm_blockquote-footer::before {
        content: "\2014\00A0";
    }

    .nm_img-fluid {
        max-width: 100%;
        height: auto;
    }

    .nm_img-thumbnail {
        padding: 0.25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        max-width: 100%;
        height: auto;
    }

    .nm_figure {
        display: inline-block;
    }

    .nm_figure-img {
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .nm_figure-caption {
        font-size: 90%;
        color: #6c757d;
    }

    .nm_pre-scrollable {
        max-height: 340px;
        overflow-y: scroll;
    }

    .nm_container {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    @media (min-width: 576px) {
        .nm_container {
            max-width: 540px;
        }
    }

    @media (min-width: 768px) {
        .nm_container {
            max-width: 720px;
        }
    }

    @media (min-width: 992px) {
        .nm_container {
            max-width: 960px;
        }
    }

    @media (min-width: 1200px) {
        .nm_container {
            max-width: 1140px;
        }
    }

    .nm_container-fluid, .nm_container-sm, .nm_container-md, .nm_container-lg, .nm_container-xl {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    @media (min-width: 576px) {
        .nm_container, .nm_container-sm {
            max-width: 540px;
        }
    }

    @media (min-width: 768px) {
        .nm_container, .nm_container-sm, .nm_container-md {
            max-width: 720px;
        }
    }

    @media (min-width: 992px) {
        .nm_container, .nm_container-sm, .nm_container-md, .nm_container-lg {
            max-width: 960px;
        }
    }

    @media (min-width: 1200px) {
        .nm_container, .nm_container-sm, .nm_container-md, .nm_container-lg, .nm_container-xl {
            max-width: 1140px;
        }
    }

    .nm_row {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .nm_no-gutters {
        margin-right: 0;
        margin-left: 0;
    }

    .nm_no-gutters > .nm_col,
    .nm_no-gutters > [class*="col-"] {
        padding-right: 0;
        padding-left: 0;
    }

    .nm_col-1, .nm_col-2, .nm_col-3, .nm_col-4, .nm_col-5, .nm_col-6, .nm_col-7, .nm_col-8, .nm_col-9, .nm_col-10, .nm_col-11, .nm_col-12, .nm_col,
    .nm_col-auto, .nm_col-sm-1, .nm_col-sm-2, .nm_col-sm-3, .nm_col-sm-4, .nm_col-sm-5, .nm_col-sm-6, .nm_col-sm-7, .nm_col-sm-8, .nm_col-sm-9, .nm_col-sm-10, .nm_col-sm-11, .nm_col-sm-12, .nm_col-sm,
    .nm_col-sm-auto, .nm_col-md-1, .nm_col-md-2, .nm_col-md-3, .nm_col-md-4, .nm_col-md-5, .nm_col-md-6, .nm_col-md-7, .nm_col-md-8, .nm_col-md-9, .nm_col-md-10, .nm_col-md-11, .nm_col-md-12, .nm_col-md,
    .nm_col-md-auto, .nm_col-lg-1, .nm_col-lg-2, .nm_col-lg-3, .nm_col-lg-4, .nm_col-lg-5, .nm_col-lg-6, .nm_col-lg-7, .nm_col-lg-8, .nm_col-lg-9, .nm_col-lg-10, .nm_col-lg-11, .nm_col-lg-12, .nm_col-lg,
    .nm_col-lg-auto, .nm_col-xl-1, .nm_col-xl-2, .nm_col-xl-3, .nm_col-xl-4, .nm_col-xl-5, .nm_col-xl-6, .nm_col-xl-7, .nm_col-xl-8, .nm_col-xl-9, .nm_col-xl-10, .nm_col-xl-11, .nm_col-xl-12, .nm_col-xl,
    .nm_col-xl-auto {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .nm_col {
        -ms-flex-preferred-size: 0;
        flex-basis: 0;
        -ms-flex-positive: 1;
        flex-grow: 1;
        max-width: 100%;
    }

    .nm_row-cols-1 > * {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
    }

    .nm_row-cols-2 > * {
        -ms-flex: 0 0 50%;
        flex: 0 0 50%;
        max-width: 50%;
    }

    .nm_row-cols-3 > * {
        -ms-flex: 0 0 33.333333%;
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }

    .nm_row-cols-4 > * {
        -ms-flex: 0 0 25%;
        flex: 0 0 25%;
        max-width: 25%;
    }

    .nm_row-cols-5 > * {
        -ms-flex: 0 0 20%;
        flex: 0 0 20%;
        max-width: 20%;
    }

    .nm_row-cols-6 > * {
        -ms-flex: 0 0 16.666667%;
        flex: 0 0 16.666667%;
        max-width: 16.666667%;
    }

    .nm_col-auto {
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: auto;
        max-width: 100%;
    }

    .nm_col-1 {
        -ms-flex: 0 0 8.333333%;
        flex: 0 0 8.333333%;
        max-width: 8.333333%;
    }

    .nm_col-2 {
        -ms-flex: 0 0 16.666667%;
        flex: 0 0 16.666667%;
        max-width: 16.666667%;
    }

    .nm_col-3 {
        -ms-flex: 0 0 25%;
        flex: 0 0 25%;
        max-width: 25%;
    }

    .nm_col-4 {
        -ms-flex: 0 0 33.333333%;
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }

    .nm_col-5 {
        -ms-flex: 0 0 41.666667%;
        flex: 0 0 41.666667%;
        max-width: 41.666667%;
    }

    .nm_col-6 {
        -ms-flex: 0 0 50%;
        flex: 0 0 50%;
        max-width: 50%;
    }

    .nm_col-7 {
        -ms-flex: 0 0 58.333333%;
        flex: 0 0 58.333333%;
        max-width: 58.333333%;
    }

    .nm_col-8 {
        -ms-flex: 0 0 66.666667%;
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
    }

    .nm_col-9 {
        -ms-flex: 0 0 75%;
        flex: 0 0 75%;
        max-width: 75%;
    }

    .nm_col-10 {
        -ms-flex: 0 0 83.333333%;
        flex: 0 0 83.333333%;
        max-width: 83.333333%;
    }

    .nm_col-11 {
        -ms-flex: 0 0 91.666667%;
        flex: 0 0 91.666667%;
        max-width: 91.666667%;
    }

    .nm_col-12 {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
    }

    .nm_order-first {
        -ms-flex-order: -1;
        order: -1;
    }

    .nm_order-last {
        -ms-flex-order: 13;
        order: 13;
    }

    .nm_order-0 {
        -ms-flex-order: 0;
        order: 0;
    }

    .nm_order-1 {
        -ms-flex-order: 1;
        order: 1;
    }

    .nm_order-2 {
        -ms-flex-order: 2;
        order: 2;
    }

    .nm_order-3 {
        -ms-flex-order: 3;
        order: 3;
    }

    .nm_order-4 {
        -ms-flex-order: 4;
        order: 4;
    }

    .nm_order-5 {
        -ms-flex-order: 5;
        order: 5;
    }

    .nm_order-6 {
        -ms-flex-order: 6;
        order: 6;
    }

    .nm_order-7 {
        -ms-flex-order: 7;
        order: 7;
    }

    .nm_order-8 {
        -ms-flex-order: 8;
        order: 8;
    }

    .nm_order-9 {
        -ms-flex-order: 9;
        order: 9;
    }

    .nm_order-10 {
        -ms-flex-order: 10;
        order: 10;
    }

    .nm_order-11 {
        -ms-flex-order: 11;
        order: 11;
    }

    .nm_order-12 {
        -ms-flex-order: 12;
        order: 12;
    }

    .nm_offset-1 {
        margin-left: 8.333333%;
    }

    .nm_offset-2 {
        margin-left: 16.666667%;
    }

    .nm_offset-3 {
        margin-left: 25%;
    }

    .nm_offset-4 {
        margin-left: 33.333333%;
    }

    .nm_offset-5 {
        margin-left: 41.666667%;
    }

    .nm_offset-6 {
        margin-left: 50%;
    }

    .nm_offset-7 {
        margin-left: 58.333333%;
    }

    .nm_offset-8 {
        margin-left: 66.666667%;
    }

    .nm_offset-9 {
        margin-left: 75%;
    }

    .nm_offset-10 {
        margin-left: 83.333333%;
    }

    .nm_offset-11 {
        margin-left: 91.666667%;
    }

    @media (min-width: 576px) {
        .nm_col-sm {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;
        }
        .nm_row-cols-sm-1 > * {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .nm_row-cols-sm-2 > * {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }
        .nm_row-cols-sm-3 > * {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .nm_row-cols-sm-4 > * {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }
        .nm_row-cols-sm-5 > * {
            -ms-flex: 0 0 20%;
            flex: 0 0 20%;
            max-width: 20%;
        }
        .nm_row-cols-sm-6 > * {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }
        .nm_col-sm-auto {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            width: auto;
            max-width: 100%;
        }
        .nm_col-sm-1 {
            -ms-flex: 0 0 8.333333%;
            flex: 0 0 8.333333%;
            max-width: 8.333333%;
        }
        .nm_col-sm-2 {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }
        .nm_col-sm-3 {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }
        .nm_col-sm-4 {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .nm_col-sm-5 {
            -ms-flex: 0 0 41.666667%;
            flex: 0 0 41.666667%;
            max-width: 41.666667%;
        }
        .nm_col-sm-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }
        .nm_col-sm-7 {
            -ms-flex: 0 0 58.333333%;
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }
        .nm_col-sm-8 {
            -ms-flex: 0 0 66.666667%;
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
        .nm_col-sm-9 {
            -ms-flex: 0 0 75%;
            flex: 0 0 75%;
            max-width: 75%;
        }
        .nm_col-sm-10 {
            -ms-flex: 0 0 83.333333%;
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }
        .nm_col-sm-11 {
            -ms-flex: 0 0 91.666667%;
            flex: 0 0 91.666667%;
            max-width: 91.666667%;
        }
        .nm_col-sm-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .nm_order-sm-first {
            -ms-flex-order: -1;
            order: -1;
        }
        .nm_order-sm-last {
            -ms-flex-order: 13;
            order: 13;
        }
        .nm_order-sm-0 {
            -ms-flex-order: 0;
            order: 0;
        }
        .nm_order-sm-1 {
            -ms-flex-order: 1;
            order: 1;
        }
        .nm_order-sm-2 {
            -ms-flex-order: 2;
            order: 2;
        }
        .nm_order-sm-3 {
            -ms-flex-order: 3;
            order: 3;
        }
        .nm_order-sm-4 {
            -ms-flex-order: 4;
            order: 4;
        }
        .nm_order-sm-5 {
            -ms-flex-order: 5;
            order: 5;
        }
        .nm_order-sm-6 {
            -ms-flex-order: 6;
            order: 6;
        }
        .nm_order-sm-7 {
            -ms-flex-order: 7;
            order: 7;
        }
        .nm_order-sm-8 {
            -ms-flex-order: 8;
            order: 8;
        }
        .nm_order-sm-9 {
            -ms-flex-order: 9;
            order: 9;
        }
        .nm_order-sm-10 {
            -ms-flex-order: 10;
            order: 10;
        }
        .nm_order-sm-11 {
            -ms-flex-order: 11;
            order: 11;
        }
        .nm_order-sm-12 {
            -ms-flex-order: 12;
            order: 12;
        }
        .nm_offset-sm-0 {
            margin-left: 0;
        }
        .nm_offset-sm-1 {
            margin-left: 8.333333%;
        }
        .nm_offset-sm-2 {
            margin-left: 16.666667%;
        }
        .nm_offset-sm-3 {
            margin-left: 25%;
        }
        .nm_offset-sm-4 {
            margin-left: 33.333333%;
        }
        .nm_offset-sm-5 {
            margin-left: 41.666667%;
        }
        .nm_offset-sm-6 {
            margin-left: 50%;
        }
        .nm_offset-sm-7 {
            margin-left: 58.333333%;
        }
        .nm_offset-sm-8 {
            margin-left: 66.666667%;
        }
        .nm_offset-sm-9 {
            margin-left: 75%;
        }
        .nm_offset-sm-10 {
            margin-left: 83.333333%;
        }
        .nm_offset-sm-11 {
            margin-left: 91.666667%;
        }
    }

    @media (min-width: 768px) {
        .nm_col-md {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;
        }
        .nm_row-cols-md-1 > * {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .nm_row-cols-md-2 > * {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }
        .nm_row-cols-md-3 > * {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .nm_row-cols-md-4 > * {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }
        .nm_row-cols-md-5 > * {
            -ms-flex: 0 0 20%;
            flex: 0 0 20%;
            max-width: 20%;
        }
        .nm_row-cols-md-6 > * {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }
        .nm_col-md-auto {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            width: auto;
            max-width: 100%;
        }
        .nm_col-md-1 {
            -ms-flex: 0 0 8.333333%;
            flex: 0 0 8.333333%;
            max-width: 8.333333%;
        }
        .nm_col-md-2 {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }
        .nm_col-md-3 {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }
        .nm_col-md-4 {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .nm_col-md-5 {
            -ms-flex: 0 0 41.666667%;
            flex: 0 0 41.666667%;
            max-width: 41.666667%;
        }
        .nm_col-md-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }
        .nm_col-md-7 {
            -ms-flex: 0 0 58.333333%;
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }
        .nm_col-md-8 {
            -ms-flex: 0 0 66.666667%;
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
        .nm_col-md-9 {
            -ms-flex: 0 0 75%;
            flex: 0 0 75%;
            max-width: 75%;
        }
        .nm_col-md-10 {
            -ms-flex: 0 0 83.333333%;
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }
        .nm_col-md-11 {
            -ms-flex: 0 0 91.666667%;
            flex: 0 0 91.666667%;
            max-width: 91.666667%;
        }
        .nm_col-md-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .nm_order-md-first {
            -ms-flex-order: -1;
            order: -1;
        }
        .nm_order-md-last {
            -ms-flex-order: 13;
            order: 13;
        }
        .nm_order-md-0 {
            -ms-flex-order: 0;
            order: 0;
        }
        .nm_order-md-1 {
            -ms-flex-order: 1;
            order: 1;
        }
        .nm_order-md-2 {
            -ms-flex-order: 2;
            order: 2;
        }
        .nm_order-md-3 {
            -ms-flex-order: 3;
            order: 3;
        }
        .nm_order-md-4 {
            -ms-flex-order: 4;
            order: 4;
        }
        .nm_order-md-5 {
            -ms-flex-order: 5;
            order: 5;
        }
        .nm_order-md-6 {
            -ms-flex-order: 6;
            order: 6;
        }
        .nm_order-md-7 {
            -ms-flex-order: 7;
            order: 7;
        }
        .nm_order-md-8 {
            -ms-flex-order: 8;
            order: 8;
        }
        .nm_order-md-9 {
            -ms-flex-order: 9;
            order: 9;
        }
        .nm_order-md-10 {
            -ms-flex-order: 10;
            order: 10;
        }
        .nm_order-md-11 {
            -ms-flex-order: 11;
            order: 11;
        }
        .nm_order-md-12 {
            -ms-flex-order: 12;
            order: 12;
        }
        .nm_offset-md-0 {
            margin-left: 0;
        }
        .nm_offset-md-1 {
            margin-left: 8.333333%;
        }
        .nm_offset-md-2 {
            margin-left: 16.666667%;
        }
        .nm_offset-md-3 {
            margin-left: 25%;
        }
        .nm_offset-md-4 {
            margin-left: 33.333333%;
        }
        .nm_offset-md-5 {
            margin-left: 41.666667%;
        }
        .nm_offset-md-6 {
            margin-left: 50%;
        }
        .nm_offset-md-7 {
            margin-left: 58.333333%;
        }
        .nm_offset-md-8 {
            margin-left: 66.666667%;
        }
        .nm_offset-md-9 {
            margin-left: 75%;
        }
        .nm_offset-md-10 {
            margin-left: 83.333333%;
        }
        .nm_offset-md-11 {
            margin-left: 91.666667%;
        }
    }

    @media (min-width: 992px) {
        .nm_col-lg {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;
        }
        .nm_row-cols-lg-1 > * {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .nm_row-cols-lg-2 > * {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }
        .nm_row-cols-lg-3 > * {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .nm_row-cols-lg-4 > * {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }
        .nm_row-cols-lg-5 > * {
            -ms-flex: 0 0 20%;
            flex: 0 0 20%;
            max-width: 20%;
        }
        .nm_row-cols-lg-6 > * {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }
        .nm_col-lg-auto {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            width: auto;
            max-width: 100%;
        }
        .nm_col-lg-1 {
            -ms-flex: 0 0 8.333333%;
            flex: 0 0 8.333333%;
            max-width: 8.333333%;
        }
        .nm_col-lg-2 {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }
        .nm_col-lg-3 {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }
        .nm_col-lg-4 {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .nm_col-lg-5 {
            -ms-flex: 0 0 41.666667%;
            flex: 0 0 41.666667%;
            max-width: 41.666667%;
        }
        .nm_col-lg-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }
        .nm_col-lg-7 {
            -ms-flex: 0 0 58.333333%;
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }
        .nm_col-lg-8 {
            -ms-flex: 0 0 66.666667%;
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
        .nm_col-lg-9 {
            -ms-flex: 0 0 75%;
            flex: 0 0 75%;
            max-width: 75%;
        }
        .nm_col-lg-10 {
            -ms-flex: 0 0 83.333333%;
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }
        .nm_col-lg-11 {
            -ms-flex: 0 0 91.666667%;
            flex: 0 0 91.666667%;
            max-width: 91.666667%;
        }
        .nm_col-lg-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .nm_order-lg-first {
            -ms-flex-order: -1;
            order: -1;
        }
        .nm_order-lg-last {
            -ms-flex-order: 13;
            order: 13;
        }
        .nm_order-lg-0 {
            -ms-flex-order: 0;
            order: 0;
        }
        .nm_order-lg-1 {
            -ms-flex-order: 1;
            order: 1;
        }
        .nm_order-lg-2 {
            -ms-flex-order: 2;
            order: 2;
        }
        .nm_order-lg-3 {
            -ms-flex-order: 3;
            order: 3;
        }
        .nm_order-lg-4 {
            -ms-flex-order: 4;
            order: 4;
        }
        .nm_order-lg-5 {
            -ms-flex-order: 5;
            order: 5;
        }
        .nm_order-lg-6 {
            -ms-flex-order: 6;
            order: 6;
        }
        .nm_order-lg-7 {
            -ms-flex-order: 7;
            order: 7;
        }
        .nm_order-lg-8 {
            -ms-flex-order: 8;
            order: 8;
        }
        .nm_order-lg-9 {
            -ms-flex-order: 9;
            order: 9;
        }
        .nm_order-lg-10 {
            -ms-flex-order: 10;
            order: 10;
        }
        .nm_order-lg-11 {
            -ms-flex-order: 11;
            order: 11;
        }
        .nm_order-lg-12 {
            -ms-flex-order: 12;
            order: 12;
        }
        .nm_offset-lg-0 {
            margin-left: 0;
        }
        .nm_offset-lg-1 {
            margin-left: 8.333333%;
        }
        .nm_offset-lg-2 {
            margin-left: 16.666667%;
        }
        .nm_offset-lg-3 {
            margin-left: 25%;
        }
        .nm_offset-lg-4 {
            margin-left: 33.333333%;
        }
        .nm_offset-lg-5 {
            margin-left: 41.666667%;
        }
        .nm_offset-lg-6 {
            margin-left: 50%;
        }
        .nm_offset-lg-7 {
            margin-left: 58.333333%;
        }
        .nm_offset-lg-8 {
            margin-left: 66.666667%;
        }
        .nm_offset-lg-9 {
            margin-left: 75%;
        }
        .nm_offset-lg-10 {
            margin-left: 83.333333%;
        }
        .nm_offset-lg-11 {
            margin-left: 91.666667%;
        }
    }

    @media (min-width: 1200px) {
        .nm_col-xl {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;
        }
        .nm_row-cols-xl-1 > * {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .nm_row-cols-xl-2 > * {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }
        .nm_row-cols-xl-3 > * {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .nm_row-cols-xl-4 > * {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }
        .nm_row-cols-xl-5 > * {
            -ms-flex: 0 0 20%;
            flex: 0 0 20%;
            max-width: 20%;
        }
        .nm_row-cols-xl-6 > * {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }
        .nm_col-xl-auto {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            width: auto;
            max-width: 100%;
        }
        .nm_col-xl-1 {
            -ms-flex: 0 0 8.333333%;
            flex: 0 0 8.333333%;
            max-width: 8.333333%;
        }
        .nm_col-xl-2 {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }
        .nm_col-xl-3 {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }
        .nm_col-xl-4 {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .nm_col-xl-5 {
            -ms-flex: 0 0 41.666667%;
            flex: 0 0 41.666667%;
            max-width: 41.666667%;
        }
        .nm_col-xl-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }
        .nm_col-xl-7 {
            -ms-flex: 0 0 58.333333%;
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }
        .nm_col-xl-8 {
            -ms-flex: 0 0 66.666667%;
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
        .nm_col-xl-9 {
            -ms-flex: 0 0 75%;
            flex: 0 0 75%;
            max-width: 75%;
        }
        .nm_col-xl-10 {
            -ms-flex: 0 0 83.333333%;
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }
        .nm_col-xl-11 {
            -ms-flex: 0 0 91.666667%;
            flex: 0 0 91.666667%;
            max-width: 91.666667%;
        }
        .nm_col-xl-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .nm_order-xl-first {
            -ms-flex-order: -1;
            order: -1;
        }
        .nm_order-xl-last {
            -ms-flex-order: 13;
            order: 13;
        }
        .nm_order-xl-0 {
            -ms-flex-order: 0;
            order: 0;
        }
        .nm_order-xl-1 {
            -ms-flex-order: 1;
            order: 1;
        }
        .nm_order-xl-2 {
            -ms-flex-order: 2;
            order: 2;
        }
        .nm_order-xl-3 {
            -ms-flex-order: 3;
            order: 3;
        }
        .nm_order-xl-4 {
            -ms-flex-order: 4;
            order: 4;
        }
        .nm_order-xl-5 {
            -ms-flex-order: 5;
            order: 5;
        }
        .nm_order-xl-6 {
            -ms-flex-order: 6;
            order: 6;
        }
        .nm_order-xl-7 {
            -ms-flex-order: 7;
            order: 7;
        }
        .nm_order-xl-8 {
            -ms-flex-order: 8;
            order: 8;
        }
        .nm_order-xl-9 {
            -ms-flex-order: 9;
            order: 9;
        }
        .nm_order-xl-10 {
            -ms-flex-order: 10;
            order: 10;
        }
        .nm_order-xl-11 {
            -ms-flex-order: 11;
            order: 11;
        }
        .nm_order-xl-12 {
            -ms-flex-order: 12;
            order: 12;
        }
        .nm_offset-xl-0 {
            margin-left: 0;
        }
        .nm_offset-xl-1 {
            margin-left: 8.333333%;
        }
        .nm_offset-xl-2 {
            margin-left: 16.666667%;
        }
        .nm_offset-xl-3 {
            margin-left: 25%;
        }
        .nm_offset-xl-4 {
            margin-left: 33.333333%;
        }
        .nm_offset-xl-5 {
            margin-left: 41.666667%;
        }
        .nm_offset-xl-6 {
            margin-left: 50%;
        }
        .nm_offset-xl-7 {
            margin-left: 58.333333%;
        }
        .nm_offset-xl-8 {
            margin-left: 66.666667%;
        }
        .nm_offset-xl-9 {
            margin-left: 75%;
        }
        .nm_offset-xl-10 {
            margin-left: 83.333333%;
        }
        .nm_offset-xl-11 {
            margin-left: 91.666667%;
        }
    }

    .nm_table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        border-collapse: collapse;
    }

    .nm_table th,
    .nm_table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    .nm_table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }

    .nm_table tbody + tbody {
        border-top: 2px solid #dee2e6;
    }

    .nm_table-sm th,
    .nm_table-sm td {
        padding: 0.3rem;
    }

    .nm_table-bordered {
        border: 1px solid #dee2e6;
    }

    .nm_table-bordered th,
    .nm_table-bordered td {
        border: 1px solid #dee2e6;
    }

    .nm_table-bordered thead th,
    .nm_table-bordered thead td {
        border-bottom-width: 2px;
    }

    .nm_table-borderless th,
    .nm_table-borderless td,
    .nm_table-borderless thead th,
    .nm_table-borderless tbody + tbody {
        border: 0;
    }

    .nm_table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .nm_table-hover tbody tr:hover {
        color: #212529;
        background-color: rgba(0, 0, 0, 0.075);
    }

    .nm_table-primary,
    .nm_table-primary > th,
    .nm_table-primary > td {
        background-color: #b8daff;
    }

    .nm_table-primary th,
    .nm_table-primary td,
    .nm_table-primary thead th,
    .nm_table-primary tbody + tbody {
        border-color: #7abaff;
    }

    .nm_table-hover .nm_table-primary:hover {
        background-color: #9fcdff;
    }

    .nm_table-hover .nm_table-primary:hover > td,
    .nm_table-hover .nm_table-primary:hover > th {
        background-color: #9fcdff;
    }

    .nm_table-secondary,
    .nm_table-secondary > th,
    .nm_table-secondary > td {
        background-color: #d6d8db;
    }

    .nm_table-secondary th,
    .nm_table-secondary td,
    .nm_table-secondary thead th,
    .nm_table-secondary tbody + tbody {
        border-color: #b3b7bb;
    }

    .nm_table-hover .nm_table-secondary:hover {
        background-color: #c8cbcf;
    }

    .nm_table-hover .nm_table-secondary:hover > td,
    .nm_table-hover .nm_table-secondary:hover > th {
        background-color: #c8cbcf;
    }

    .nm_table-success,
    .nm_table-success > th,
    .nm_table-success > td {
        background-color: #c3e6cb;
    }

    .nm_table-success th,
    .nm_table-success td,
    .nm_table-success thead th,
    .nm_table-success tbody + tbody {
        border-color: #8fd19e;
    }

    .nm_table-hover .nm_table-success:hover {
        background-color: #b1dfbb;
    }

    .nm_table-hover .nm_table-success:hover > td,
    .nm_table-hover .nm_table-success:hover > th {
        background-color: #b1dfbb;
    }

    .nm_table-info,
    .nm_table-info > th,
    .nm_table-info > td {
        background-color: #bee5eb;
    }

    .nm_table-info th,
    .nm_table-info td,
    .nm_table-info thead th,
    .nm_table-info tbody + tbody {
        border-color: #86cfda;
    }

    .nm_table-hover .nm_table-info:hover {
        background-color: #abdde5;
    }

    .nm_table-hover .nm_table-info:hover > td,
    .nm_table-hover .nm_table-info:hover > th {
        background-color: #abdde5;
    }

    .nm_table-warning,
    .nm_table-warning > th,
    .nm_table-warning > td {
        background-color: #ffeeba;
    }

    .nm_table-warning th,
    .nm_table-warning td,
    .nm_table-warning thead th,
    .nm_table-warning tbody + tbody {
        border-color: #ffdf7e;
    }

    .nm_table-hover .nm_table-warning:hover {
        background-color: #ffe8a1;
    }

    .nm_table-hover .nm_table-warning:hover > td,
    .nm_table-hover .nm_table-warning:hover > th {
        background-color: #ffe8a1;
    }

    .nm_table-danger,
    .nm_table-danger > th,
    .nm_table-danger > td {
        background-color: #f5c6cb;
    }

    .nm_table-danger th,
    .nm_table-danger td,
    .nm_table-danger thead th,
    .nm_table-danger tbody + tbody {
        border-color: #ed969e;
    }

    .nm_table-hover .nm_table-danger:hover {
        background-color: #f1b0b7;
    }

    .nm_table-hover .nm_table-danger:hover > td,
    .nm_table-hover .nm_table-danger:hover > th {
        background-color: #f1b0b7;
    }

    .nm_table-light,
    .nm_table-light > th,
    .nm_table-light > td {
        background-color: #fdfdfe;
    }

    .nm_table-light th,
    .nm_table-light td,
    .nm_table-light thead th,
    .nm_table-light tbody + tbody {
        border-color: #fbfcfc;
    }

    .nm_table-hover .nm_table-light:hover {
        background-color: #ececf6;
    }

    .nm_table-hover .nm_table-light:hover > td,
    .nm_table-hover .nm_table-light:hover > th {
        background-color: #ececf6;
    }

    .nm_table-dark,
    .nm_table-dark > th,
    .nm_table-dark > td {
        background-color: #c6c8ca;
    }

    .nm_table-dark th,
    .nm_table-dark td,
    .nm_table-dark thead th,
    .nm_table-dark tbody + tbody {
        border-color: #95999c;
    }

    .nm_table-hover .nm_table-dark:hover {
        background-color: #b9bbbe;
    }

    .nm_table-hover .nm_table-dark:hover > td,
    .nm_table-hover .nm_table-dark:hover > th {
        background-color: #b9bbbe;
    }

    .nm_table-active,
    .nm_table-active > th,
    .nm_table-active > td {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .nm_table-hover .nm_table-active:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .nm_table-hover .nm_table-active:hover > td,
    .nm_table-hover .nm_table-active:hover > th {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .nm_table .nm_thead-dark th {
        color: #fff;
        background-color: #343a40;
        border-color: #454d55;
    }

    .nm_table .nm_thead-light th {
        color: #495057;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .nm_table-dark {
        color: #fff;
        background-color: #343a40;
    }

    .nm_table-dark th,
    .nm_table-dark td,
    .nm_table-dark thead th {
        border-color: #454d55;
    }

    .nm_table-dark.nm_table-bordered {
        border: 0;
    }

    .nm_table-dark.nm_table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .nm_table-dark.nm_table-hover tbody tr:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.075);
    }

    @media (max-width: 575.98px) {
        .nm_table-responsive-sm {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .nm_table-responsive-sm > .nm_table-bordered {
            border: 0;
        }
    }

    @media (max-width: 767.98px) {
        .nm_table-responsive-md {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .nm_table-responsive-md > .nm_table-bordered {
            border: 0;
        }
    }

    @media (max-width: 991.98px) {
        .nm_table-responsive-lg {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .nm_table-responsive-lg > .nm_table-bordered {
            border: 0;
        }
    }

    @media (max-width: 1199.98px) {
        .nm_table-responsive-xl {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .nm_table-responsive-xl > .nm_table-bordered {
            border: 0;
        }
    }

    .nm_table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .nm_table-responsive > .nm_table-bordered {
        border: 0;
    }

    .nm_form-control {
        display: block;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_form-control {
            transition: none;
        }
    }

    .nm_form-control::-ms-expand {
        background-color: transparent;
        border: 0;
    }

    .nm_form-control:-moz-focusring {
        color: transparent;
        text-shadow: 0 0 0 #495057;
    }

    .nm_form-control:focus {
        color: #495057;
        background-color: #fff;
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .nm_form-control::-webkit-input-placeholder {
        color: #6c757d;
        opacity: 1;
    }

    .nm_form-control::-moz-placeholder {
        color: #6c757d;
        opacity: 1;
    }

    .nm_form-control:-ms-input-placeholder {
        color: #6c757d;
        opacity: 1;
    }

    .nm_form-control::-ms-input-placeholder {
        color: #6c757d;
        opacity: 1;
    }

    .nm_form-control::placeholder {
        color: #6c757d;
        opacity: 1;
    }

    .nm_form-control:disabled, .nm_form-control[readonly] {
        background-color: #e9ecef;
        opacity: 1;
    }

    select.nm_form-control:focus::-ms-value {
        color: #495057;
        background-color: #fff;
    }

    .nm_form-control-file,
    .nm_form-control-range {
        display: block;
        width: 100%;
    }

    .nm_col-form-label {
        padding-top: calc(0.375rem + 1px);
        padding-bottom: calc(0.375rem + 1px);
        margin-bottom: 0;
        font-size: inherit;
        line-height: 1.5;
    }

    .nm_col-form-label-lg {
        padding-top: calc(0.5rem + 1px);
        padding-bottom: calc(0.5rem + 1px);
        font-size: 1.25rem;
        line-height: 1.5;
    }

    .nm_col-form-label-sm {
        padding-top: calc(0.25rem + 1px);
        padding-bottom: calc(0.25rem + 1px);
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .nm_form-control-plaintext {
        display: block;
        width: 100%;
        padding: 0.375rem 0;
        margin-bottom: 0;
        font-size: 1rem;
        line-height: 1.5;
        color: #212529;
        background-color: transparent;
        border: solid transparent;
        border-width: 1px 0;
    }

    .nm_form-control-plaintext.nm_form-control-sm, .nm_form-control-plaintext.nm_form-control-lg {
        padding-right: 0;
        padding-left: 0;
    }

    .nm_form-control-sm {
        height: calc(1.5em + 0.5rem + 2px);
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    .nm_form-control-lg {
        height: calc(1.5em + 1rem + 2px);
        padding: 0.5rem 1rem;
        font-size: 1.25rem;
        line-height: 1.5;
        border-radius: 0.3rem;
    }

    select.nm_form-control[size], select.nm_form-control[multiple] {
        height: auto;
    }

    textarea.nm_form-control {
        height: auto;
    }

    .nm_form-group {
        margin-bottom: 1rem;
    }

    .nm_form-text {
        display: block;
        margin-top: 0.25rem;
    }

    .nm_form-row {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -5px;
        margin-left: -5px;
    }

    .nm_form-row > .nm_col,
    .nm_form-row > [class*="col-"] {
        padding-right: 5px;
        padding-left: 5px;
    }

    .nm_form-check {
        position: relative;
        display: block;
        padding-left: 1.25rem;
    }

    .nm_form-check-input {
        position: absolute;
        margin-top: 0.3rem;
        margin-left: -1.25rem;
    }

    .nm_form-check-input[disabled] ~ .nm_form-check-label,
    .nm_form-check-input:disabled ~ .nm_form-check-label {
        color: #6c757d;
    }

    .nm_form-check-label {
        margin-bottom: 0;
    }

    .nm_form-check-inline {
        display: -ms-inline-flexbox;
        display: inline-flex;
        -ms-flex-align: center;
        align-items: center;
        padding-left: 0;
        margin-right: 0.75rem;
    }

    .nm_form-check-inline .nm_form-check-input {
        position: static;
        margin-top: 0;
        margin-right: 0.3125rem;
        margin-left: 0;
    }

    .nm_valid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 80%;
        color: #28a745;
    }

    .nm_valid-tooltip {
        position: absolute;
        top: 100%;
        z-index: 5;
        display: none;
        max-width: 100%;
        padding: 0.25rem 0.5rem;
        margin-top: .1rem;
        font-size: 0.875rem;
        line-height: 1.5;
        color: #fff;
        background-color: rgba(40, 167, 69, 0.9);
        border-radius: 0.25rem;
    }

    .nm_was-validated :valid ~ .nm_valid-feedback,
    .nm_was-validated :valid ~ .nm_valid-tooltip,
    .nm_is-valid ~ .nm_valid-feedback,
    .nm_is-valid ~ .nm_valid-tooltip {
        display: block;
    }

    .nm_was-validated .nm_form-control:valid, .nm_form-control.nm_is-valid {
        border-color: #28a745;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .nm_was-validated .nm_form-control:valid:focus, .nm_form-control.nm_is-valid:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .nm_was-validated textarea.nm_form-control:valid, textarea.nm_form-control.nm_is-valid {
        padding-right: calc(1.5em + 0.75rem);
        background-position: top calc(0.375em + 0.1875rem) right calc(0.375em + 0.1875rem);
    }

    .nm_was-validated .nm_custom-select:valid, .nm_custom-select.nm_is-valid {
        border-color: #28a745;
        padding-right: calc(0.75em + 2.3125rem);
        background: url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") no-repeat right 0.75rem center/8px 10px, url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e") #fff no-repeat center right 1.75rem/calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .nm_was-validated .nm_custom-select:valid:focus, .nm_custom-select.nm_is-valid:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .nm_was-validated .nm_form-check-input:valid ~ .nm_form-check-label, .nm_form-check-input.nm_is-valid ~ .nm_form-check-label {
        color: #28a745;
    }

    .nm_was-validated .nm_form-check-input:valid ~ .nm_valid-feedback,
    .nm_was-validated .nm_form-check-input:valid ~ .nm_valid-tooltip, .nm_form-check-input.nm_is-valid ~ .nm_valid-feedback,
    .nm_form-check-input.nm_is-valid ~ .nm_valid-tooltip {
        display: block;
    }

    .nm_was-validated .nm_custom-control-input:valid ~ .nm_custom-control-label, .nm_custom-control-input.nm_is-valid ~ .nm_custom-control-label {
        color: #28a745;
    }

    .nm_was-validated .nm_custom-control-input:valid ~ .nm_custom-control-label::before, .nm_custom-control-input.nm_is-valid ~ .nm_custom-control-label::before {
        border-color: #28a745;
    }

    .nm_was-validated .nm_custom-control-input:valid:checked ~ .nm_custom-control-label::before, .nm_custom-control-input.nm_is-valid:checked ~ .nm_custom-control-label::before {
        border-color: #34ce57;
        background-color: #34ce57;
    }

    .nm_was-validated .nm_custom-control-input:valid:focus ~ .nm_custom-control-label::before, .nm_custom-control-input.nm_is-valid:focus ~ .nm_custom-control-label::before {
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .nm_was-validated .nm_custom-control-input:valid:focus:not(:checked) ~ .nm_custom-control-label::before, .nm_custom-control-input.nm_is-valid:focus:not(:checked) ~ .nm_custom-control-label::before {
        border-color: #28a745;
    }

    .nm_was-validated .nm_custom-file-input:valid ~ .nm_custom-file-label, .nm_custom-file-input.nm_is-valid ~ .nm_custom-file-label {
        border-color: #28a745;
    }

    .nm_was-validated .nm_custom-file-input:valid:focus ~ .nm_custom-file-label, .nm_custom-file-input.nm_is-valid:focus ~ .nm_custom-file-label {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .nm_invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 80%;
        color: #dc3545;
    }

    .nm_invalid-tooltip {
        position: absolute;
        top: 100%;
        z-index: 5;
        display: none;
        max-width: 100%;
        padding: 0.25rem 0.5rem;
        margin-top: .1rem;
        font-size: 0.875rem;
        line-height: 1.5;
        color: #fff;
        background-color: rgba(220, 53, 69, 0.9);
        border-radius: 0.25rem;
    }

    .nm_was-validated :invalid ~ .nm_invalid-feedback,
    .nm_was-validated :invalid ~ .nm_invalid-tooltip,
    .nm_is-invalid ~ .nm_invalid-feedback,
    .nm_is-invalid ~ .nm_invalid-tooltip {
        display: block;
    }

    .nm_was-validated .nm_form-control:invalid, .nm_form-control.nm_is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .nm_was-validated .nm_form-control:invalid:focus, .nm_form-control.nm_is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .nm_was-validated textarea.nm_form-control:invalid, textarea.nm_form-control.nm_is-invalid {
        padding-right: calc(1.5em + 0.75rem);
        background-position: top calc(0.375em + 0.1875rem) right calc(0.375em + 0.1875rem);
    }

    .nm_was-validated .nm_custom-select:invalid, .nm_custom-select.nm_is-invalid {
        border-color: #dc3545;
        padding-right: calc(0.75em + 2.3125rem);
        background: url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") no-repeat right 0.75rem center/8px 10px, url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e") #fff no-repeat center right 1.75rem/calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .nm_was-validated .nm_custom-select:invalid:focus, .nm_custom-select.nm_is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .nm_was-validated .nm_form-check-input:invalid ~ .nm_form-check-label, .nm_form-check-input.nm_is-invalid ~ .nm_form-check-label {
        color: #dc3545;
    }

    .nm_was-validated .nm_form-check-input:invalid ~ .nm_invalid-feedback,
    .nm_was-validated .nm_form-check-input:invalid ~ .nm_invalid-tooltip, .nm_form-check-input.nm_is-invalid ~ .nm_invalid-feedback,
    .nm_form-check-input.nm_is-invalid ~ .nm_invalid-tooltip {
        display: block;
    }

    .nm_was-validated .nm_custom-control-input:invalid ~ .nm_custom-control-label, .nm_custom-control-input.nm_is-invalid ~ .nm_custom-control-label {
        color: #dc3545;
    }

    .nm_was-validated .nm_custom-control-input:invalid ~ .nm_custom-control-label::before, .nm_custom-control-input.nm_is-invalid ~ .nm_custom-control-label::before {
        border-color: #dc3545;
    }

    .nm_was-validated .nm_custom-control-input:invalid:checked ~ .nm_custom-control-label::before, .nm_custom-control-input.nm_is-invalid:checked ~ .nm_custom-control-label::before {
        border-color: #e4606d;
        background-color: #e4606d;
    }

    .nm_was-validated .nm_custom-control-input:invalid:focus ~ .nm_custom-control-label::before, .nm_custom-control-input.nm_is-invalid:focus ~ .nm_custom-control-label::before {
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .nm_was-validated .nm_custom-control-input:invalid:focus:not(:checked) ~ .nm_custom-control-label::before, .nm_custom-control-input.nm_is-invalid:focus:not(:checked) ~ .nm_custom-control-label::before {
        border-color: #dc3545;
    }

    .nm_was-validated .nm_custom-file-input:invalid ~ .nm_custom-file-label, .nm_custom-file-input.nm_is-invalid ~ .nm_custom-file-label {
        border-color: #dc3545;
    }

    .nm_was-validated .nm_custom-file-input:invalid:focus ~ .nm_custom-file-label, .nm_custom-file-input.nm_is-invalid:focus ~ .nm_custom-file-label {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .nm_form-inline {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-flow: row wrap;
        flex-flow: row wrap;
        -ms-flex-align: center;
        align-items: center;
    }

    .nm_form-inline .nm_form-check {
        width: 100%;
    }

    @media (min-width: 576px) {
        .nm_form-inline label {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: center;
            justify-content: center;
            margin-bottom: 0;
        }
        .nm_form-inline .nm_form-group {
            display: -ms-flexbox;
            display: flex;
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            -ms-flex-flow: row wrap;
            flex-flow: row wrap;
            -ms-flex-align: center;
            align-items: center;
            margin-bottom: 0;
        }
        .nm_form-inline .nm_form-control {
            display: inline-block;
            width: auto;
            vertical-align: middle;
        }
        .nm_form-inline .nm_form-control-plaintext {
            display: inline-block;
        }
        .nm_form-inline .nm_input-group,
        .nm_form-inline .nm_custom-select {
            width: auto;
        }
        .nm_form-inline .nm_form-check {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: center;
            justify-content: center;
            width: auto;
            padding-left: 0;
        }
        .nm_form-inline .nm_form-check-input {
            position: relative;
            -ms-flex-negative: 0;
            flex-shrink: 0;
            margin-top: 0;
            margin-right: 0.25rem;
            margin-left: 0;
        }
        .nm_form-inline .nm_custom-control {
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: center;
            justify-content: center;
        }
        .nm_form-inline .nm_custom-control-label {
            margin-bottom: 0;
        }
    }

    .nm_btn {
        display: inline-block;
        font-weight: 400;
        color: #212529;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_btn {
            transition: none;
        }
    }

    .nm_btn:hover {
        color: #212529;
        text-decoration: none;
    }

    .nm_btn:focus, .nm_btn.nm_focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .nm_btn.nm_disabled, .nm_btn:disabled {
        opacity: 0.65;
    }

    a.nm_btn.nm_disabled,
    fieldset:disabled a.nm_btn {
        pointer-events: none;
    }

    .nm_btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .nm_btn-primary:hover {
        color: #fff;
        background-color: #0069d9;
        border-color: #0062cc;
    }

    .nm_btn-primary:focus, .nm_btn-primary.nm_focus {
        color: #fff;
        background-color: #0069d9;
        border-color: #0062cc;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
    }

    .nm_btn-primary.nm_disabled, .nm_btn-primary:disabled {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .nm_btn-primary:not(:disabled):not(.nm_disabled):active, .nm_btn-primary:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-primary.nm_dropdown-toggle {
        color: #fff;
        background-color: #0062cc;
        border-color: #005cbf;
    }

    .nm_btn-primary:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-primary:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-primary.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
    }

    .nm_btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .nm_btn-secondary:hover {
        color: #fff;
        background-color: #5a6268;
        border-color: #545b62;
    }

    .nm_btn-secondary:focus, .nm_btn-secondary.nm_focus {
        color: #fff;
        background-color: #5a6268;
        border-color: #545b62;
        box-shadow: 0 0 0 0.2rem rgba(130, 138, 145, 0.5);
    }

    .nm_btn-secondary.nm_disabled, .nm_btn-secondary:disabled {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .nm_btn-secondary:not(:disabled):not(.nm_disabled):active, .nm_btn-secondary:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-secondary.nm_dropdown-toggle {
        color: #fff;
        background-color: #545b62;
        border-color: #4e555b;
    }

    .nm_btn-secondary:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-secondary:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-secondary.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(130, 138, 145, 0.5);
    }

    .nm_btn-success {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }

    .nm_btn-success:hover {
        color: #fff;
        background-color: #218838;
        border-color: #1e7e34;
    }

    .nm_btn-success:focus, .nm_btn-success.nm_focus {
        color: #fff;
        background-color: #218838;
        border-color: #1e7e34;
        box-shadow: 0 0 0 0.2rem rgba(72, 180, 97, 0.5);
    }

    .nm_btn-success.nm_disabled, .nm_btn-success:disabled {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }

    .nm_btn-success:not(:disabled):not(.nm_disabled):active, .nm_btn-success:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-success.nm_dropdown-toggle {
        color: #fff;
        background-color: #1e7e34;
        border-color: #1c7430;
    }

    .nm_btn-success:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-success:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-success.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(72, 180, 97, 0.5);
    }

    .nm_btn-info {
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .nm_btn-info:hover {
        color: #fff;
        background-color: #138496;
        border-color: #117a8b;
    }

    .nm_btn-info:focus, .nm_btn-info.nm_focus {
        color: #fff;
        background-color: #138496;
        border-color: #117a8b;
        box-shadow: 0 0 0 0.2rem rgba(58, 176, 195, 0.5);
    }

    .nm_btn-info.nm_disabled, .nm_btn-info:disabled {
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .nm_btn-info:not(:disabled):not(.nm_disabled):active, .nm_btn-info:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-info.nm_dropdown-toggle {
        color: #fff;
        background-color: #117a8b;
        border-color: #10707f;
    }

    .nm_btn-info:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-info:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-info.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(58, 176, 195, 0.5);
    }

    .nm_btn-warning {
        color: #212529;
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .nm_btn-warning:hover {
        color: #212529;
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .nm_btn-warning:focus, .nm_btn-warning.nm_focus {
        color: #212529;
        background-color: #e0a800;
        border-color: #d39e00;
        box-shadow: 0 0 0 0.2rem rgba(222, 170, 12, 0.5);
    }

    .nm_btn-warning.nm_disabled, .nm_btn-warning:disabled {
        color: #212529;
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .nm_btn-warning:not(:disabled):not(.nm_disabled):active, .nm_btn-warning:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-warning.nm_dropdown-toggle {
        color: #212529;
        background-color: #d39e00;
        border-color: #c69500;
    }

    .nm_btn-warning:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-warning:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-warning.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(222, 170, 12, 0.5);
    }

    .nm_btn-danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .nm_btn-danger:hover {
        color: #fff;
        background-color: #c82333;
        border-color: #bd2130;
    }

    .nm_btn-danger:focus, .nm_btn-danger.nm_focus {
        color: #fff;
        background-color: #c82333;
        border-color: #bd2130;
        box-shadow: 0 0 0 0.2rem rgba(225, 83, 97, 0.5);
    }

    .nm_btn-danger.nm_disabled, .nm_btn-danger:disabled {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .nm_btn-danger:not(:disabled):not(.nm_disabled):active, .nm_btn-danger:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-danger.nm_dropdown-toggle {
        color: #fff;
        background-color: #bd2130;
        border-color: #b21f2d;
    }

    .nm_btn-danger:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-danger:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-danger.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(225, 83, 97, 0.5);
    }

    .nm_btn-light {
        color: #212529;
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }

    .nm_btn-light:hover {
        color: #212529;
        background-color: #e2e6ea;
        border-color: #dae0e5;
    }

    .nm_btn-light:focus, .nm_btn-light.nm_focus {
        color: #212529;
        background-color: #e2e6ea;
        border-color: #dae0e5;
        box-shadow: 0 0 0 0.2rem rgba(216, 217, 219, 0.5);
    }

    .nm_btn-light.nm_disabled, .nm_btn-light:disabled {
        color: #212529;
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }

    .nm_btn-light:not(:disabled):not(.nm_disabled):active, .nm_btn-light:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-light.nm_dropdown-toggle {
        color: #212529;
        background-color: #dae0e5;
        border-color: #d3d9df;
    }

    .nm_btn-light:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-light:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-light.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(216, 217, 219, 0.5);
    }

    .nm_btn-dark {
        color: #fff;
        background-color: #343a40;
        border-color: #343a40;
    }

    .nm_btn-dark:hover {
        color: #fff;
        background-color: #23272b;
        border-color: #1d2124;
    }

    .nm_btn-dark:focus, .nm_btn-dark.nm_focus {
        color: #fff;
        background-color: #23272b;
        border-color: #1d2124;
        box-shadow: 0 0 0 0.2rem rgba(82, 88, 93, 0.5);
    }

    .nm_btn-dark.nm_disabled, .nm_btn-dark:disabled {
        color: #fff;
        background-color: #343a40;
        border-color: #343a40;
    }

    .nm_btn-dark:not(:disabled):not(.nm_disabled):active, .nm_btn-dark:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-dark.nm_dropdown-toggle {
        color: #fff;
        background-color: #1d2124;
        border-color: #171a1d;
    }

    .nm_btn-dark:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-dark:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-dark.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(82, 88, 93, 0.5);
    }

    .nm_btn-outline-primary {
        color: #007bff;
        border-color: #007bff;
    }

    .nm_btn-outline-primary:hover {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .nm_btn-outline-primary:focus, .nm_btn-outline-primary.nm_focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5);
    }

    .nm_btn-outline-primary.nm_disabled, .nm_btn-outline-primary:disabled {
        color: #007bff;
        background-color: transparent;
    }

    .nm_btn-outline-primary:not(:disabled):not(.nm_disabled):active, .nm_btn-outline-primary:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-outline-primary.nm_dropdown-toggle {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .nm_btn-outline-primary:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-outline-primary:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-outline-primary.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5);
    }

    .nm_btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }

    .nm_btn-outline-secondary:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .nm_btn-outline-secondary:focus, .nm_btn-outline-secondary.nm_focus {
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5);
    }

    .nm_btn-outline-secondary.nm_disabled, .nm_btn-outline-secondary:disabled {
        color: #6c757d;
        background-color: transparent;
    }

    .nm_btn-outline-secondary:not(:disabled):not(.nm_disabled):active, .nm_btn-outline-secondary:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-outline-secondary.nm_dropdown-toggle {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .nm_btn-outline-secondary:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-outline-secondary:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-outline-secondary.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5);
    }

    .nm_btn-outline-success {
        color: #28a745;
        border-color: #28a745;
    }

    .nm_btn-outline-success:hover {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }

    .nm_btn-outline-success:focus, .nm_btn-outline-success.nm_focus {
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5);
    }

    .nm_btn-outline-success.nm_disabled, .nm_btn-outline-success:disabled {
        color: #28a745;
        background-color: transparent;
    }

    .nm_btn-outline-success:not(:disabled):not(.nm_disabled):active, .nm_btn-outline-success:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-outline-success.nm_dropdown-toggle {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }

    .nm_btn-outline-success:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-outline-success:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-outline-success.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5);
    }

    .nm_btn-outline-info {
        color: #17a2b8;
        border-color: #17a2b8;
    }

    .nm_btn-outline-info:hover {
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .nm_btn-outline-info:focus, .nm_btn-outline-info.nm_focus {
        box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.5);
    }

    .nm_btn-outline-info.nm_disabled, .nm_btn-outline-info:disabled {
        color: #17a2b8;
        background-color: transparent;
    }

    .nm_btn-outline-info:not(:disabled):not(.nm_disabled):active, .nm_btn-outline-info:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-outline-info.nm_dropdown-toggle {
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .nm_btn-outline-info:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-outline-info:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-outline-info.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.5);
    }

    .nm_btn-outline-warning {
        color: #ffc107;
        border-color: #ffc107;
    }

    .nm_btn-outline-warning:hover {
        color: #212529;
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .nm_btn-outline-warning:focus, .nm_btn-outline-warning.nm_focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.5);
    }

    .nm_btn-outline-warning.nm_disabled, .nm_btn-outline-warning:disabled {
        color: #ffc107;
        background-color: transparent;
    }

    .nm_btn-outline-warning:not(:disabled):not(.nm_disabled):active, .nm_btn-outline-warning:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-outline-warning.nm_dropdown-toggle {
        color: #212529;
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .nm_btn-outline-warning:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-outline-warning:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-outline-warning.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.5);
    }

    .nm_btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }

    .nm_btn-outline-danger:hover {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .nm_btn-outline-danger:focus, .nm_btn-outline-danger.nm_focus {
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5);
    }

    .nm_btn-outline-danger.nm_disabled, .nm_btn-outline-danger:disabled {
        color: #dc3545;
        background-color: transparent;
    }

    .nm_btn-outline-danger:not(:disabled):not(.nm_disabled):active, .nm_btn-outline-danger:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-outline-danger.nm_dropdown-toggle {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .nm_btn-outline-danger:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-outline-danger:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-outline-danger.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5);
    }

    .nm_btn-outline-light {
        color: #f8f9fa;
        border-color: #f8f9fa;
    }

    .nm_btn-outline-light:hover {
        color: #212529;
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }

    .nm_btn-outline-light:focus, .nm_btn-outline-light.nm_focus {
        box-shadow: 0 0 0 0.2rem rgba(248, 249, 250, 0.5);
    }

    .nm_btn-outline-light.nm_disabled, .nm_btn-outline-light:disabled {
        color: #f8f9fa;
        background-color: transparent;
    }

    .nm_btn-outline-light:not(:disabled):not(.nm_disabled):active, .nm_btn-outline-light:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-outline-light.nm_dropdown-toggle {
        color: #212529;
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }

    .nm_btn-outline-light:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-outline-light:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-outline-light.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(248, 249, 250, 0.5);
    }

    .nm_btn-outline-dark {
        color: #343a40;
        border-color: #343a40;
    }

    .nm_btn-outline-dark:hover {
        color: #fff;
        background-color: #343a40;
        border-color: #343a40;
    }

    .nm_btn-outline-dark:focus, .nm_btn-outline-dark.nm_focus {
        box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.5);
    }

    .nm_btn-outline-dark.nm_disabled, .nm_btn-outline-dark:disabled {
        color: #343a40;
        background-color: transparent;
    }

    .nm_btn-outline-dark:not(:disabled):not(.nm_disabled):active, .nm_btn-outline-dark:not(:disabled):not(.nm_disabled).nm_active,
    .show > .nm_btn-outline-dark.nm_dropdown-toggle {
        color: #fff;
        background-color: #343a40;
        border-color: #343a40;
    }

    .nm_btn-outline-dark:not(:disabled):not(.nm_disabled):active:focus, .nm_btn-outline-dark:not(:disabled):not(.nm_disabled).nm_active:focus,
    .show > .nm_btn-outline-dark.nm_dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.5);
    }

    .nm_btn-link {
        font-weight: 400;
        color: #007bff;
        text-decoration: none;
    }

    .nm_btn-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .nm_btn-link:focus, .nm_btn-link.nm_focus {
        text-decoration: underline;
        box-shadow: none;
    }

    .nm_btn-link:disabled, .nm_btn-link.nm_disabled {
        color: #6c757d;
        pointer-events: none;
    }

    .nm_btn-lg, .nm_btn-group-lg > .nm_btn {
        padding: 0.5rem 1rem;
        font-size: 1.25rem;
        line-height: 1.5;
        border-radius: 0.3rem;
    }

    .nm_btn-sm, .nm_btn-group-sm > .nm_btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    .nm_btn-block {
        display: block;
        width: 100%;
    }

    .nm_btn-block + .nm_btn-block {
        margin-top: 0.5rem;
    }

    input[type="submit"].nm_btn-block,
    input[type="reset"].nm_btn-block,
    input[type="button"].nm_btn-block {
        width: 100%;
    }

    .nm_fade {
        transition: opacity 0.15s linear;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_fade {
            transition: none;
        }
    }

    .nm_fade:not(.show) {
        opacity: 0;
    }

    .nm_collapse:not(.show) {
        display: none;
    }

    .nm_collapsing {
        position: relative;
        height: 0;
        overflow: hidden;
        transition: height 0.35s ease;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_collapsing {
            transition: none;
        }
    }

    .nm_dropup,
    .nm_dropright,
    .nm_dropdown,
    .nm_dropleft {
        position: relative;
    }

    .nm_dropdown-toggle {
        white-space: nowrap;
    }

    .nm_dropdown-toggle::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
    }

    .nm_dropdown-toggle:empty::after {
        margin-left: 0;
    }

    .nm_dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 10rem;
        padding: 0.5rem 0;
        margin: 0.125rem 0 0;
        font-size: 1rem;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 0.25rem;
    }

    .nm_dropdown-menu-left {
        right: auto;
        left: 0;
    }

    .nm_dropdown-menu-right {
        right: 0;
        left: auto;
    }

    @media (min-width: 576px) {
        .nm_dropdown-menu-sm-left {
            right: auto;
            left: 0;
        }
        .nm_dropdown-menu-sm-right {
            right: 0;
            left: auto;
        }
    }

    @media (min-width: 768px) {
        .nm_dropdown-menu-md-left {
            right: auto;
            left: 0;
        }
        .nm_dropdown-menu-md-right {
            right: 0;
            left: auto;
        }
    }

    @media (min-width: 992px) {
        .nm_dropdown-menu-lg-left {
            right: auto;
            left: 0;
        }
        .nm_dropdown-menu-lg-right {
            right: 0;
            left: auto;
        }
    }

    @media (min-width: 1200px) {
        .nm_dropdown-menu-xl-left {
            right: auto;
            left: 0;
        }
        .nm_dropdown-menu-xl-right {
            right: 0;
            left: auto;
        }
    }

    .nm_dropup .nm_dropdown-menu {
        top: auto;
        bottom: 100%;
        margin-top: 0;
        margin-bottom: 0.125rem;
    }

    .nm_dropup .nm_dropdown-toggle::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0;
        border-right: 0.3em solid transparent;
        border-bottom: 0.3em solid;
        border-left: 0.3em solid transparent;
    }

    .nm_dropup .nm_dropdown-toggle:empty::after {
        margin-left: 0;
    }

    .nm_dropright .nm_dropdown-menu {
        top: 0;
        right: auto;
        left: 100%;
        margin-top: 0;
        margin-left: 0.125rem;
    }

    .nm_dropright .nm_dropdown-toggle::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid transparent;
        border-right: 0;
        border-bottom: 0.3em solid transparent;
        border-left: 0.3em solid;
    }

    .nm_dropright .nm_dropdown-toggle:empty::after {
        margin-left: 0;
    }

    .nm_dropright .nm_dropdown-toggle::after {
        vertical-align: 0;
    }

    .nm_dropleft .nm_dropdown-menu {
        top: 0;
        right: 100%;
        left: auto;
        margin-top: 0;
        margin-right: 0.125rem;
    }

    .nm_dropleft .nm_dropdown-toggle::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
    }

    .nm_dropleft .nm_dropdown-toggle::after {
        display: none;
    }

    .nm_dropleft .nm_dropdown-toggle::before {
        display: inline-block;
        margin-right: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid transparent;
        border-right: 0.3em solid;
        border-bottom: 0.3em solid transparent;
    }

    .nm_dropleft .nm_dropdown-toggle:empty::after {
        margin-left: 0;
    }

    .nm_dropleft .nm_dropdown-toggle::before {
        vertical-align: 0;
    }

    .nm_dropdown-menu[x-placement^="top"], .nm_dropdown-menu[x-placement^="right"], .nm_dropdown-menu[x-placement^="bottom"], .nm_dropdown-menu[x-placement^="left"] {
        right: auto;
        bottom: auto;
    }

    .nm_dropdown-divider {
        height: 0;
        margin: 0.5rem 0;
        overflow: hidden;
        border-top: 1px solid #e9ecef;
    }

    .nm_dropdown-item {
        display: block;
        width: 100%;
        padding: 0.25rem 1.5rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
    }

    .nm_dropdown-item:hover, .nm_dropdown-item:focus {
        color: #16181b;
        text-decoration: none;
        background-color: #f8f9fa;
    }

    .nm_dropdown-item.nm_active, .nm_dropdown-item:active {
        color: #fff;
        text-decoration: none;
        background-color: #007bff;
    }

    .nm_dropdown-item.nm_disabled, .nm_dropdown-item:disabled {
        color: #6c757d;
        pointer-events: none;
        background-color: transparent;
    }

    .nm_dropdown-menu.show {
        display: block;
    }

    .nm_dropdown-header {
        display: block;
        padding: 0.5rem 1.5rem;
        margin-bottom: 0;
        font-size: 0.875rem;
        color: #6c757d;
        white-space: nowrap;
    }

    .nm_dropdown-item-text {
        display: block;
        padding: 0.25rem 1.5rem;
        color: #212529;
    }

    .nm_btn-group,
    .nm_btn-group-vertical {
        position: relative;
        display: -ms-inline-flexbox;
        display: inline-flex;
        vertical-align: middle;
    }

    .nm_btn-group > .nm_btn,
    .nm_btn-group-vertical > .nm_btn {
        position: relative;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
    }

    .nm_btn-group > .nm_btn:hover,
    .nm_btn-group-vertical > .nm_btn:hover {
        z-index: 1;
    }

    .nm_btn-group > .nm_btn:focus, .nm_btn-group > .nm_btn:active, .nm_btn-group > .nm_btn.nm_active,
    .nm_btn-group-vertical > .nm_btn:focus,
    .nm_btn-group-vertical > .nm_btn:active,
    .nm_btn-group-vertical > .nm_btn.nm_active {
        z-index: 1;
    }

    .nm_btn-toolbar {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -ms-flex-pack: start;
        justify-content: flex-start;
    }

    .nm_btn-toolbar .nm_input-group {
        width: auto;
    }

    .nm_btn-group > .nm_btn:not(:first-child),
    .nm_btn-group > .nm_btn-group:not(:first-child) {
        margin-left: -1px;
    }

    .nm_btn-group > .nm_btn:not(:last-child):not(.nm_dropdown-toggle),
    .nm_btn-group > .nm_btn-group:not(:last-child) > .nm_btn {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .nm_btn-group > .nm_btn:not(:first-child),
    .nm_btn-group > .nm_btn-group:not(:first-child) > .nm_btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .nm_dropdown-toggle-split {
        padding-right: 0.5625rem;
        padding-left: 0.5625rem;
    }

    .nm_dropdown-toggle-split::after,
    .nm_dropup .nm_dropdown-toggle-split::after,
    .nm_dropright .nm_dropdown-toggle-split::after {
        margin-left: 0;
    }

    .nm_dropleft .nm_dropdown-toggle-split::before {
        margin-right: 0;
    }

    .nm_btn-sm + .nm_dropdown-toggle-split, .nm_btn-group-sm > .nm_btn + .nm_dropdown-toggle-split {
        padding-right: 0.375rem;
        padding-left: 0.375rem;
    }

    .nm_btn-lg + .nm_dropdown-toggle-split, .nm_btn-group-lg > .nm_btn + .nm_dropdown-toggle-split {
        padding-right: 0.75rem;
        padding-left: 0.75rem;
    }

    .nm_btn-group-vertical {
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-align: start;
        align-items: flex-start;
        -ms-flex-pack: center;
        justify-content: center;
    }

    .nm_btn-group-vertical > .nm_btn,
    .nm_btn-group-vertical > .nm_btn-group {
        width: 100%;
    }

    .nm_btn-group-vertical > .nm_btn:not(:first-child),
    .nm_btn-group-vertical > .nm_btn-group:not(:first-child) {
        margin-top: -1px;
    }

    .nm_btn-group-vertical > .nm_btn:not(:last-child):not(.nm_dropdown-toggle),
    .nm_btn-group-vertical > .nm_btn-group:not(:last-child) > .nm_btn {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .nm_btn-group-vertical > .nm_btn:not(:first-child),
    .nm_btn-group-vertical > .nm_btn-group:not(:first-child) > .nm_btn {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .nm_btn-group-toggle > .nm_btn,
    .nm_btn-group-toggle > .nm_btn-group > .nm_btn {
        margin-bottom: 0;
    }

    .nm_btn-group-toggle > .nm_btn input[type="radio"],
    .nm_btn-group-toggle > .nm_btn input[type="checkbox"],
    .nm_btn-group-toggle > .nm_btn-group > .nm_btn input[type="radio"],
    .nm_btn-group-toggle > .nm_btn-group > .nm_btn input[type="checkbox"] {
        position: absolute;
        clip: rect(0, 0, 0, 0);
        pointer-events: none;
    }

    .nm_input-group {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -ms-flex-align: stretch;
        align-items: stretch;
        width: 100%;
    }

    .nm_input-group > .nm_form-control,
    .nm_input-group > .nm_form-control-plaintext,
    .nm_input-group > .nm_custom-select,
    .nm_input-group > .nm_custom-file {
        position: relative;
        -ms-flex: 1 1 0%;
        flex: 1 1 0%;
        min-width: 0;
        margin-bottom: 0;
    }

    .nm_input-group > .nm_form-control + .nm_form-control,
    .nm_input-group > .nm_form-control + .nm_custom-select,
    .nm_input-group > .nm_form-control + .nm_custom-file,
    .nm_input-group > .nm_form-control-plaintext + .nm_form-control,
    .nm_input-group > .nm_form-control-plaintext + .nm_custom-select,
    .nm_input-group > .nm_form-control-plaintext + .nm_custom-file,
    .nm_input-group > .nm_custom-select + .nm_form-control,
    .nm_input-group > .nm_custom-select + .nm_custom-select,
    .nm_input-group > .nm_custom-select + .nm_custom-file,
    .nm_input-group > .nm_custom-file + .nm_form-control,
    .nm_input-group > .nm_custom-file + .nm_custom-select,
    .nm_input-group > .nm_custom-file + .nm_custom-file {
        margin-left: -1px;
    }

    .nm_input-group > .nm_form-control:focus,
    .nm_input-group > .nm_custom-select:focus,
    .nm_input-group > .nm_custom-file .nm_custom-file-input:focus ~ .nm_custom-file-label {
        z-index: 3;
    }

    .nm_input-group > .nm_custom-file .nm_custom-file-input:focus {
        z-index: 4;
    }

    .nm_input-group > .nm_form-control:not(:last-child),
    .nm_input-group > .nm_custom-select:not(:last-child) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .nm_input-group > .nm_form-control:not(:first-child),
    .nm_input-group > .nm_custom-select:not(:first-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .nm_input-group > .nm_custom-file {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
    }

    .nm_input-group > .nm_custom-file:not(:last-child) .nm_custom-file-label,
    .nm_input-group > .nm_custom-file:not(:last-child) .nm_custom-file-label::after {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .nm_input-group > .nm_custom-file:not(:first-child) .nm_custom-file-label {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .nm_input-group-prepend,
    .nm_input-group-append {
        display: -ms-flexbox;
        display: flex;
    }

    .nm_input-group-prepend .nm_btn,
    .nm_input-group-append .nm_btn {
        position: relative;
        z-index: 2;
    }

    .nm_input-group-prepend .nm_btn:focus,
    .nm_input-group-append .nm_btn:focus {
        z-index: 3;
    }

    .nm_input-group-prepend .nm_btn + .nm_btn,
    .nm_input-group-prepend .nm_btn + .nm_input-group-text,
    .nm_input-group-prepend .nm_input-group-text + .nm_input-group-text,
    .nm_input-group-prepend .nm_input-group-text + .nm_btn,
    .nm_input-group-append .nm_btn + .nm_btn,
    .nm_input-group-append .nm_btn + .nm_input-group-text,
    .nm_input-group-append .nm_input-group-text + .nm_input-group-text,
    .nm_input-group-append .nm_input-group-text + .nm_btn {
        margin-left: -1px;
    }

    .nm_input-group-prepend {
        margin-right: -1px;
    }

    .nm_input-group-append {
        margin-left: -1px;
    }

    .nm_input-group-text {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding: 0.375rem 0.75rem;
        margin-bottom: 0;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        text-align: center;
        white-space: nowrap;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .nm_input-group-text input[type="radio"],
    .nm_input-group-text input[type="checkbox"] {
        margin-top: 0;
    }

    .nm_input-group-lg > .nm_form-control:not(textarea),
    .nm_input-group-lg > .nm_custom-select {
        height: calc(1.5em + 1rem + 2px);
    }

    .nm_input-group-lg > .nm_form-control,
    .nm_input-group-lg > .nm_custom-select,
    .nm_input-group-lg > .nm_input-group-prepend > .nm_input-group-text,
    .nm_input-group-lg > .nm_input-group-append > .nm_input-group-text,
    .nm_input-group-lg > .nm_input-group-prepend > .nm_btn,
    .nm_input-group-lg > .nm_input-group-append > .nm_btn {
        padding: 0.5rem 1rem;
        font-size: 1.25rem;
        line-height: 1.5;
        border-radius: 0.3rem;
    }

    .nm_input-group-sm > .nm_form-control:not(textarea),
    .nm_input-group-sm > .nm_custom-select {
        height: calc(1.5em + 0.5rem + 2px);
    }

    .nm_input-group-sm > .nm_form-control,
    .nm_input-group-sm > .nm_custom-select,
    .nm_input-group-sm > .nm_input-group-prepend > .nm_input-group-text,
    .nm_input-group-sm > .nm_input-group-append > .nm_input-group-text,
    .nm_input-group-sm > .nm_input-group-prepend > .nm_btn,
    .nm_input-group-sm > .nm_input-group-append > .nm_btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    .nm_input-group-lg > .nm_custom-select,
    .nm_input-group-sm > .nm_custom-select {
        padding-right: 1.75rem;
    }

    .nm_input-group > .nm_input-group-prepend > .nm_btn,
    .nm_input-group > .nm_input-group-prepend > .nm_input-group-text,
    .nm_input-group > .nm_input-group-append:not(:last-child) > .nm_btn,
    .nm_input-group > .nm_input-group-append:not(:last-child) > .nm_input-group-text,
    .nm_input-group > .nm_input-group-append:last-child > .nm_btn:not(:last-child):not(.nm_dropdown-toggle),
    .nm_input-group > .nm_input-group-append:last-child > .nm_input-group-text:not(:last-child) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .nm_input-group > .nm_input-group-append > .nm_btn,
    .nm_input-group > .nm_input-group-append > .nm_input-group-text,
    .nm_input-group > .nm_input-group-prepend:not(:first-child) > .nm_btn,
    .nm_input-group > .nm_input-group-prepend:not(:first-child) > .nm_input-group-text,
    .nm_input-group > .nm_input-group-prepend:first-child > .nm_btn:not(:first-child),
    .nm_input-group > .nm_input-group-prepend:first-child > .nm_input-group-text:not(:first-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .nm_custom-control {
        position: relative;
        display: block;
        min-height: 1.5rem;
        padding-left: 1.5rem;
    }

    .nm_custom-control-inline {
        display: -ms-inline-flexbox;
        display: inline-flex;
        margin-right: 1rem;
    }

    .nm_custom-control-input {
        position: absolute;
        left: 0;
        z-index: -1;
        width: 1rem;
        height: 1.25rem;
        opacity: 0;
    }

    .nm_custom-control-input:checked ~ .nm_custom-control-label::before {
        color: #fff;
        border-color: #007bff;
        background-color: #007bff;
    }

    .nm_custom-control-input:focus ~ .nm_custom-control-label::before {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .nm_custom-control-input:focus:not(:checked) ~ .nm_custom-control-label::before {
        border-color: #80bdff;
    }

    .nm_custom-control-input:not(:disabled):active ~ .nm_custom-control-label::before {
        color: #fff;
        background-color: #b3d7ff;
        border-color: #b3d7ff;
    }

    .nm_custom-control-input[disabled] ~ .nm_custom-control-label, .nm_custom-control-input:disabled ~ .nm_custom-control-label {
        color: #6c757d;
    }

    .nm_custom-control-input[disabled] ~ .nm_custom-control-label::before, .nm_custom-control-input:disabled ~ .nm_custom-control-label::before {
        background-color: #e9ecef;
    }

    .nm_custom-control-label {
        position: relative;
        margin-bottom: 0;
        vertical-align: top;
    }

    .nm_custom-control-label::before {
        position: absolute;
        top: 0.25rem;
        left: -1.5rem;
        display: block;
        width: 1rem;
        height: 1rem;
        pointer-events: none;
        content: "";
        background-color: #fff;
        border: #adb5bd solid 1px;
    }

    .nm_custom-control-label::after {
        position: absolute;
        top: 0.25rem;
        left: -1.5rem;
        display: block;
        width: 1rem;
        height: 1rem;
        content: "";
        background: no-repeat 50% / 50% 50%;
    }

    .nm_custom-checkbox .nm_custom-control-label::before {
        border-radius: 0.25rem;
    }

    .nm_custom-checkbox .nm_custom-control-input:checked ~ .nm_custom-control-label::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
    }

    .nm_custom-checkbox .nm_custom-control-input:indeterminate ~ .nm_custom-control-label::before {
        border-color: #007bff;
        background-color: #007bff;
    }

    .nm_custom-checkbox .nm_custom-control-input:indeterminate ~ .nm_custom-control-label::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='4' height='4' viewBox='0 0 4 4'%3e%3cpath stroke='%23fff' d='M0 2h4'/%3e%3c/svg%3e");
    }

    .nm_custom-checkbox .nm_custom-control-input:disabled:checked ~ .nm_custom-control-label::before {
        background-color: rgba(0, 123, 255, 0.5);
    }

    .nm_custom-checkbox .nm_custom-control-input:disabled:indeterminate ~ .nm_custom-control-label::before {
        background-color: rgba(0, 123, 255, 0.5);
    }

    .nm_custom-radio .nm_custom-control-label::before {
        border-radius: 50%;
    }

    .nm_custom-radio .nm_custom-control-input:checked ~ .nm_custom-control-label::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='12' height='12' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    }

    .nm_custom-radio .nm_custom-control-input:disabled:checked ~ .nm_custom-control-label::before {
        background-color: rgba(0, 123, 255, 0.5);
    }

    .nm_custom-switch {
        padding-left: 2.25rem;
    }

    .nm_custom-switch .nm_custom-control-label::before {
        left: -2.25rem;
        width: 1.75rem;
        pointer-events: all;
        border-radius: 0.5rem;
    }

    .nm_custom-switch .nm_custom-control-label::after {
        top: calc(0.25rem + 2px);
        left: calc(-2.25rem + 2px);
        width: calc(1rem - 4px);
        height: calc(1rem - 4px);
        background-color: #adb5bd;
        border-radius: 0.5rem;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-transform 0.15s ease-in-out;
        transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-transform 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_custom-switch .nm_custom-control-label::after {
            transition: none;
        }
    }

    .nm_custom-switch .nm_custom-control-input:checked ~ .nm_custom-control-label::after {
        background-color: #fff;
        -webkit-transform: translateX(0.75rem);
        transform: translateX(0.75rem);
    }

    .nm_custom-switch .nm_custom-control-input:disabled:checked ~ .nm_custom-control-label::before {
        background-color: rgba(0, 123, 255, 0.5);
    }

    .nm_custom-select {
        display: inline-block;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        vertical-align: middle;
        background: #fff url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") no-repeat right 0.75rem center/8px 10px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .nm_custom-select:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .nm_custom-select:focus::-ms-value {
        color: #495057;
        background-color: #fff;
    }

    .nm_custom-select[multiple], .nm_custom-select[size]:not([size="1"]) {
        height: auto;
        padding-right: 0.75rem;
        background-image: none;
    }

    .nm_custom-select:disabled {
        color: #6c757d;
        background-color: #e9ecef;
    }

    .nm_custom-select::-ms-expand {
        display: none;
    }

    .nm_custom-select:-moz-focusring {
        color: transparent;
        text-shadow: 0 0 0 #495057;
    }

    .nm_custom-select-sm {
        height: calc(1.5em + 0.5rem + 2px);
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        padding-left: 0.5rem;
        font-size: 0.875rem;
    }

    .nm_custom-select-lg {
        height: calc(1.5em + 1rem + 2px);
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-left: 1rem;
        font-size: 1.25rem;
    }

    .nm_custom-file {
        position: relative;
        display: inline-block;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        margin-bottom: 0;
    }

    .nm_custom-file-input {
        position: relative;
        z-index: 2;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        margin: 0;
        opacity: 0;
    }

    .nm_custom-file-input:focus ~ .nm_custom-file-label {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .nm_custom-file-input[disabled] ~ .nm_custom-file-label,
    .nm_custom-file-input:disabled ~ .nm_custom-file-label {
        background-color: #e9ecef;
    }

    .nm_custom-file-input:lang(en) ~ .nm_custom-file-label::after {
        content: "Browse";
    }

    .nm_custom-file-input ~ .nm_custom-file-label[data-browse]::after {
        content: attr(data-browse);
    }

    .nm_custom-file-label {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .nm_custom-file-label::after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 3;
        display: block;
        height: calc(1.5em + 0.75rem);
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
        color: #495057;
        content: "Browse";
        background-color: #e9ecef;
        border-left: inherit;
        border-radius: 0 0.25rem 0.25rem 0;
    }

    .nm_custom-range {
        width: 100%;
        height: 1.4rem;
        padding: 0;
        background-color: transparent;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .nm_custom-range:focus {
        outline: none;
    }

    .nm_custom-range:focus::-webkit-slider-thumb {
        box-shadow: 0 0 0 1px #fff, 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .nm_custom-range:focus::-moz-range-thumb {
        box-shadow: 0 0 0 1px #fff, 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .nm_custom-range:focus::-ms-thumb {
        box-shadow: 0 0 0 1px #fff, 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .nm_custom-range::-moz-focus-outer {
        border: 0;
    }

    .nm_custom-range::-webkit-slider-thumb {
        width: 1rem;
        height: 1rem;
        margin-top: -0.25rem;
        background-color: #007bff;
        border: 0;
        border-radius: 1rem;
        -webkit-transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        -webkit-appearance: none;
        appearance: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_custom-range::-webkit-slider-thumb {
            -webkit-transition: none;
            transition: none;
        }
    }

    .nm_custom-range::-webkit-slider-thumb:active {
        background-color: #b3d7ff;
    }

    .nm_custom-range::-webkit-slider-runnable-track {
        width: 100%;
        height: 0.5rem;
        color: transparent;
        cursor: pointer;
        background-color: #dee2e6;
        border-color: transparent;
        border-radius: 1rem;
    }

    .nm_custom-range::-moz-range-thumb {
        width: 1rem;
        height: 1rem;
        background-color: #007bff;
        border: 0;
        border-radius: 1rem;
        -moz-transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        -moz-appearance: none;
        appearance: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_custom-range::-moz-range-thumb {
            -moz-transition: none;
            transition: none;
        }
    }

    .nm_custom-range::-moz-range-thumb:active {
        background-color: #b3d7ff;
    }

    .nm_custom-range::-moz-range-track {
        width: 100%;
        height: 0.5rem;
        color: transparent;
        cursor: pointer;
        background-color: #dee2e6;
        border-color: transparent;
        border-radius: 1rem;
    }

    .nm_custom-range::-ms-thumb {
        width: 1rem;
        height: 1rem;
        margin-top: 0;
        margin-right: 0.2rem;
        margin-left: 0.2rem;
        background-color: #007bff;
        border: 0;
        border-radius: 1rem;
        -ms-transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        appearance: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_custom-range::-ms-thumb {
            -ms-transition: none;
            transition: none;
        }
    }

    .nm_custom-range::-ms-thumb:active {
        background-color: #b3d7ff;
    }

    .nm_custom-range::-ms-track {
        width: 100%;
        height: 0.5rem;
        color: transparent;
        cursor: pointer;
        background-color: transparent;
        border-color: transparent;
        border-width: 0.5rem;
    }

    .nm_custom-range::-ms-fill-lower {
        background-color: #dee2e6;
        border-radius: 1rem;
    }

    .nm_custom-range::-ms-fill-upper {
        margin-right: 15px;
        background-color: #dee2e6;
        border-radius: 1rem;
    }

    .nm_custom-range:disabled::-webkit-slider-thumb {
        background-color: #adb5bd;
    }

    .nm_custom-range:disabled::-webkit-slider-runnable-track {
        cursor: default;
    }

    .nm_custom-range:disabled::-moz-range-thumb {
        background-color: #adb5bd;
    }

    .nm_custom-range:disabled::-moz-range-track {
        cursor: default;
    }

    .nm_custom-range:disabled::-ms-thumb {
        background-color: #adb5bd;
    }

    .nm_custom-control-label::before,
    .nm_custom-file-label,
    .nm_custom-select {
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_custom-control-label::before,
        .nm_custom-file-label,
        .nm_custom-select {
            transition: none;
        }
    }

    .nm_nav {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
    }

    .nm_nav-link {
        display: block;
        padding: 0.5rem 1rem;
    }

    .nm_nav-link:hover, .nm_nav-link:focus {
        text-decoration: none;
    }

    .nm_nav-link.nm_disabled {
        color: #6c757d;
        pointer-events: none;
        cursor: default;
    }

    .nm_nav-tabs {
        border-bottom: 1px solid #dee2e6;
    }

    .nm_nav-tabs .nm_nav-item {
        margin-bottom: -1px;
    }

    .nm_nav-tabs .nm_nav-link {
        border: 1px solid transparent;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
    }

    .nm_nav-tabs .nm_nav-link:hover, .nm_nav-tabs .nm_nav-link:focus {
        border-color: #e9ecef #e9ecef #dee2e6;
    }

    .nm_nav-tabs .nm_nav-link.nm_disabled {
        color: #6c757d;
        background-color: transparent;
        border-color: transparent;
    }

    .nm_nav-tabs .nm_nav-link.nm_active,
    .nm_nav-tabs .nm_nav-item.show .nm_nav-link {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }

    .nm_nav-tabs .nm_dropdown-menu {
        margin-top: -1px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .nm_nav-pills .nm_nav-link {
        border-radius: 0.25rem;
    }

    .nm_nav-pills .nm_nav-link.nm_active,
    .nm_nav-pills .show > .nm_nav-link {
        color: #fff;
        background-color: #007bff;
    }

    .nm_nav-fill .nm_nav-item {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        text-align: center;
    }

    .nm_nav-justified .nm_nav-item {
        -ms-flex-preferred-size: 0;
        flex-basis: 0;
        -ms-flex-positive: 1;
        flex-grow: 1;
        text-align: center;
    }

    .nm_tab-content > .nm_tab-pane {
        display: none;
    }

    .nm_tab-content > .nm_active {
        display: block;
    }

    .nm_navbar {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-pack: justify;
        justify-content: space-between;
        padding: 0.5rem 1rem;
    }

    .nm_navbar .nm_container,
    .nm_navbar .nm_container-fluid, .nm_navbar .nm_container-sm, .nm_navbar .nm_container-md, .nm_navbar .nm_container-lg, .nm_navbar .nm_container-xl {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    .nm_navbar-brand {
        display: inline-block;
        padding-top: 0.3125rem;
        padding-bottom: 0.3125rem;
        margin-right: 1rem;
        font-size: 1.25rem;
        line-height: inherit;
        white-space: nowrap;
    }

    .nm_navbar-brand:hover, .nm_navbar-brand:focus {
        text-decoration: none;
    }

    .nm_navbar-nav {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
    }

    .nm_navbar-nav .nm_nav-link {
        padding-right: 0;
        padding-left: 0;
    }

    .nm_navbar-nav .nm_dropdown-menu {
        position: static;
        float: none;
    }

    .nm_navbar-text {
        display: inline-block;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .nm_navbar-collapse {
        -ms-flex-preferred-size: 100%;
        flex-basis: 100%;
        -ms-flex-positive: 1;
        flex-grow: 1;
        -ms-flex-align: center;
        align-items: center;
    }

    .nm_navbar-toggler {
        padding: 0.25rem 0.75rem;
        font-size: 1.25rem;
        line-height: 1;
        background-color: transparent;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }

    .nm_navbar-toggler:hover, .nm_navbar-toggler:focus {
        text-decoration: none;
    }

    .nm_navbar-toggler-icon {
        display: inline-block;
        width: 1.5em;
        height: 1.5em;
        vertical-align: middle;
        content: "";
        background: no-repeat center center;
        background-size: 100% 100%;
    }

    @media (max-width: 575.98px) {
        .nm_navbar-expand-sm > .nm_container,
        .nm_navbar-expand-sm > .nm_container-fluid, .nm_navbar-expand-sm > .nm_container-sm, .nm_navbar-expand-sm > .nm_container-md, .nm_navbar-expand-sm > .nm_container-lg, .nm_navbar-expand-sm > .nm_container-xl {
            padding-right: 0;
            padding-left: 0;
        }
    }

    @media (min-width: 576px) {
        .nm_navbar-expand-sm {
            -ms-flex-flow: row nowrap;
            flex-flow: row nowrap;
            -ms-flex-pack: start;
            justify-content: flex-start;
        }
        .nm_navbar-expand-sm .nm_navbar-nav {
            -ms-flex-direction: row;
            flex-direction: row;
        }
        .nm_navbar-expand-sm .nm_navbar-nav .nm_dropdown-menu {
            position: absolute;
        }
        .nm_navbar-expand-sm .nm_navbar-nav .nm_nav-link {
            padding-right: 0.5rem;
            padding-left: 0.5rem;
        }
        .nm_navbar-expand-sm > .nm_container,
        .nm_navbar-expand-sm > .nm_container-fluid, .nm_navbar-expand-sm > .nm_container-sm, .nm_navbar-expand-sm > .nm_container-md, .nm_navbar-expand-sm > .nm_container-lg, .nm_navbar-expand-sm > .nm_container-xl {
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap;
        }
        .nm_navbar-expand-sm .nm_navbar-collapse {
            display: -ms-flexbox !important;
            display: flex !important;
            -ms-flex-preferred-size: auto;
            flex-basis: auto;
        }
        .nm_navbar-expand-sm .nm_navbar-toggler {
            display: none;
        }
    }

    @media (max-width: 767.98px) {
        .nm_navbar-expand-md > .nm_container,
        .nm_navbar-expand-md > .nm_container-fluid, .nm_navbar-expand-md > .nm_container-sm, .nm_navbar-expand-md > .nm_container-md, .nm_navbar-expand-md > .nm_container-lg, .nm_navbar-expand-md > .nm_container-xl {
            padding-right: 0;
            padding-left: 0;
        }
    }

    @media (min-width: 768px) {
        .nm_navbar-expand-md {
            -ms-flex-flow: row nowrap;
            flex-flow: row nowrap;
            -ms-flex-pack: start;
            justify-content: flex-start;
        }
        .nm_navbar-expand-md .nm_navbar-nav {
            -ms-flex-direction: row;
            flex-direction: row;
        }
        .nm_navbar-expand-md .nm_navbar-nav .nm_dropdown-menu {
            position: absolute;
        }
        .nm_navbar-expand-md .nm_navbar-nav .nm_nav-link {
            padding-right: 0.5rem;
            padding-left: 0.5rem;
        }
        .nm_navbar-expand-md > .nm_container,
        .nm_navbar-expand-md > .nm_container-fluid, .nm_navbar-expand-md > .nm_container-sm, .nm_navbar-expand-md > .nm_container-md, .nm_navbar-expand-md > .nm_container-lg, .nm_navbar-expand-md > .nm_container-xl {
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap;
        }
        .nm_navbar-expand-md .nm_navbar-collapse {
            display: -ms-flexbox !important;
            display: flex !important;
            -ms-flex-preferred-size: auto;
            flex-basis: auto;
        }
        .nm_navbar-expand-md .nm_navbar-toggler {
            display: none;
        }
    }

    @media (max-width: 991.98px) {
        .nm_navbar-expand-lg > .nm_container,
        .nm_navbar-expand-lg > .nm_container-fluid, .nm_navbar-expand-lg > .nm_container-sm, .nm_navbar-expand-lg > .nm_container-md, .nm_navbar-expand-lg > .nm_container-lg, .nm_navbar-expand-lg > .nm_container-xl {
            padding-right: 0;
            padding-left: 0;
        }
    }

    @media (min-width: 992px) {
        .nm_navbar-expand-lg {
            -ms-flex-flow: row nowrap;
            flex-flow: row nowrap;
            -ms-flex-pack: start;
            justify-content: flex-start;
        }
        .nm_navbar-expand-lg .nm_navbar-nav {
            -ms-flex-direction: row;
            flex-direction: row;
        }
        .nm_navbar-expand-lg .nm_navbar-nav .nm_dropdown-menu {
            position: absolute;
        }
        .nm_navbar-expand-lg .nm_navbar-nav .nm_nav-link {
            padding-right: 0.5rem;
            padding-left: 0.5rem;
        }
        .nm_navbar-expand-lg > .nm_container,
        .nm_navbar-expand-lg > .nm_container-fluid, .nm_navbar-expand-lg > .nm_container-sm, .nm_navbar-expand-lg > .nm_container-md, .nm_navbar-expand-lg > .nm_container-lg, .nm_navbar-expand-lg > .nm_container-xl {
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap;
        }
        .nm_navbar-expand-lg .nm_navbar-collapse {
            display: -ms-flexbox !important;
            display: flex !important;
            -ms-flex-preferred-size: auto;
            flex-basis: auto;
        }
        .nm_navbar-expand-lg .nm_navbar-toggler {
            display: none;
        }
    }

    @media (max-width: 1199.98px) {
        .nm_navbar-expand-xl > .nm_container,
        .nm_navbar-expand-xl > .nm_container-fluid, .nm_navbar-expand-xl > .nm_container-sm, .nm_navbar-expand-xl > .nm_container-md, .nm_navbar-expand-xl > .nm_container-lg, .nm_navbar-expand-xl > .nm_container-xl {
            padding-right: 0;
            padding-left: 0;
        }
    }

    @media (min-width: 1200px) {
        .nm_navbar-expand-xl {
            -ms-flex-flow: row nowrap;
            flex-flow: row nowrap;
            -ms-flex-pack: start;
            justify-content: flex-start;
        }
        .nm_navbar-expand-xl .nm_navbar-nav {
            -ms-flex-direction: row;
            flex-direction: row;
        }
        .nm_navbar-expand-xl .nm_navbar-nav .nm_dropdown-menu {
            position: absolute;
        }
        .nm_navbar-expand-xl .nm_navbar-nav .nm_nav-link {
            padding-right: 0.5rem;
            padding-left: 0.5rem;
        }
        .nm_navbar-expand-xl > .nm_container,
        .nm_navbar-expand-xl > .nm_container-fluid, .nm_navbar-expand-xl > .nm_container-sm, .nm_navbar-expand-xl > .nm_container-md, .nm_navbar-expand-xl > .nm_container-lg, .nm_navbar-expand-xl > .nm_container-xl {
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap;
        }
        .nm_navbar-expand-xl .nm_navbar-collapse {
            display: -ms-flexbox !important;
            display: flex !important;
            -ms-flex-preferred-size: auto;
            flex-basis: auto;
        }
        .nm_navbar-expand-xl .nm_navbar-toggler {
            display: none;
        }
    }

    .nm_navbar-expand {
        -ms-flex-flow: row nowrap;
        flex-flow: row nowrap;
        -ms-flex-pack: start;
        justify-content: flex-start;
    }

    .nm_navbar-expand > .nm_container,
    .nm_navbar-expand > .nm_container-fluid, .nm_navbar-expand > .nm_container-sm, .nm_navbar-expand > .nm_container-md, .nm_navbar-expand > .nm_container-lg, .nm_navbar-expand > .nm_container-xl {
        padding-right: 0;
        padding-left: 0;
    }

    .nm_navbar-expand .nm_navbar-nav {
        -ms-flex-direction: row;
        flex-direction: row;
    }

    .nm_navbar-expand .nm_navbar-nav .nm_dropdown-menu {
        position: absolute;
    }

    .nm_navbar-expand .nm_navbar-nav .nm_nav-link {
        padding-right: 0.5rem;
        padding-left: 0.5rem;
    }

    .nm_navbar-expand > .nm_container,
    .nm_navbar-expand > .nm_container-fluid, .nm_navbar-expand > .nm_container-sm, .nm_navbar-expand > .nm_container-md, .nm_navbar-expand > .nm_container-lg, .nm_navbar-expand > .nm_container-xl {
        -ms-flex-wrap: nowrap;
        flex-wrap: nowrap;
    }

    .nm_navbar-expand .nm_navbar-collapse {
        display: -ms-flexbox !important;
        display: flex !important;
        -ms-flex-preferred-size: auto;
        flex-basis: auto;
    }

    .nm_navbar-expand .nm_navbar-toggler {
        display: none;
    }

    .nm_navbar-light .nm_navbar-brand {
        color: rgba(0, 0, 0, 0.9);
    }

    .nm_navbar-light .nm_navbar-brand:hover, .nm_navbar-light .nm_navbar-brand:focus {
        color: rgba(0, 0, 0, 0.9);
    }

    .nm_navbar-light .nm_navbar-nav .nm_nav-link {
        color: rgba(0, 0, 0, 0.5);
    }

    .nm_navbar-light .nm_navbar-nav .nm_nav-link:hover, .nm_navbar-light .nm_navbar-nav .nm_nav-link:focus {
        color: rgba(0, 0, 0, 0.7);
    }

    .nm_navbar-light .nm_navbar-nav .nm_nav-link.nm_disabled {
        color: rgba(0, 0, 0, 0.3);
    }

    .nm_navbar-light .nm_navbar-nav .show > .nm_nav-link,
    .nm_navbar-light .nm_navbar-nav .nm_active > .nm_nav-link,
    .nm_navbar-light .nm_navbar-nav .nm_nav-link.show,
    .nm_navbar-light .nm_navbar-nav .nm_nav-link.nm_active {
        color: rgba(0, 0, 0, 0.9);
    }

    .nm_navbar-light .nm_navbar-toggler {
        color: rgba(0, 0, 0, 0.5);
        border-color: rgba(0, 0, 0, 0.1);
    }

    .nm_navbar-light .nm_navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(0, 0, 0, 0.5)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .nm_navbar-light .nm_navbar-text {
        color: rgba(0, 0, 0, 0.5);
    }

    .nm_navbar-light .nm_navbar-text a {
        color: rgba(0, 0, 0, 0.9);
    }

    .nm_navbar-light .nm_navbar-text a:hover, .nm_navbar-light .nm_navbar-text a:focus {
        color: rgba(0, 0, 0, 0.9);
    }

    .nm_navbar-dark .nm_navbar-brand {
        color: #fff;
    }

    .nm_navbar-dark .nm_navbar-brand:hover, .nm_navbar-dark .nm_navbar-brand:focus {
        color: #fff;
    }

    .nm_navbar-dark .nm_navbar-nav .nm_nav-link {
        color: rgba(255, 255, 255, 0.5);
    }

    .nm_navbar-dark .nm_navbar-nav .nm_nav-link:hover, .nm_navbar-dark .nm_navbar-nav .nm_nav-link:focus {
        color: rgba(255, 255, 255, 0.75);
    }

    .nm_navbar-dark .nm_navbar-nav .nm_nav-link.nm_disabled {
        color: rgba(255, 255, 255, 0.25);
    }

    .nm_navbar-dark .nm_navbar-nav .show > .nm_nav-link,
    .nm_navbar-dark .nm_navbar-nav .nm_active > .nm_nav-link,
    .nm_navbar-dark .nm_navbar-nav .nm_nav-link.show,
    .nm_navbar-dark .nm_navbar-nav .nm_nav-link.nm_active {
        color: #fff;
    }

    .nm_navbar-dark .nm_navbar-toggler {
        color: rgba(255, 255, 255, 0.5);
        border-color: rgba(255, 255, 255, 0.1);
    }

    .nm_navbar-dark .nm_navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.5)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .nm_navbar-dark .nm_navbar-text {
        color: rgba(255, 255, 255, 0.5);
    }

    .nm_navbar-dark .nm_navbar-text a {
        color: #fff;
    }

    .nm_navbar-dark .nm_navbar-text a:hover, .nm_navbar-dark .nm_navbar-text a:focus {
        color: #fff;
    }

    .nm_card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.25rem;
    }

    .nm_card > hr {
        margin-right: 0;
        margin-left: 0;
    }

    .nm_card > .nm_list-group:first-child .nm_list-group-item:first-child {
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
    }

    .nm_card > .nm_list-group:last-child .nm_list-group-item:last-child {
        border-bottom-right-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }

    .nm_card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        min-height: 1px;
        padding: 1.25rem;
    }

    .nm_card-title {
        margin-bottom: 0.75rem;
    }

    .nm_card-subtitle {
        margin-top: -0.375rem;
        margin-bottom: 0;
    }

    .nm_card-text:last-child {
        margin-bottom: 0;
    }

    .nm_card-link:hover {
        text-decoration: none;
    }

    .nm_card-link + .nm_card-link {
        margin-left: 1.25rem;
    }

    .nm_card-header {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgba(0, 0, 0, 0.03);
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .nm_card-header:first-child {
        border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
    }

    .nm_card-header + .nm_list-group .nm_list-group-item:first-child {
        border-top: 0;
    }

    .nm_card-footer {
        padding: 0.75rem 1.25rem;
        background-color: rgba(0, 0, 0, 0.03);
        border-top: 1px solid rgba(0, 0, 0, 0.125);
    }

    .nm_card-footer:last-child {
        border-radius: 0 0 calc(0.25rem - 1px) calc(0.25rem - 1px);
    }

    .nm_card-header-tabs {
        margin-right: -0.625rem;
        margin-bottom: -0.75rem;
        margin-left: -0.625rem;
        border-bottom: 0;
    }

    .nm_card-header-pills {
        margin-right: -0.625rem;
        margin-left: -0.625rem;
    }

    .nm_card-img-overlay {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        padding: 1.25rem;
    }

    .nm_card-img,
    .nm_card-img-top,
    .nm_card-img-bottom {
        -ms-flex-negative: 0;
        flex-shrink: 0;
        width: 100%;
    }

    .nm_card-img,
    .nm_card-img-top {
        border-top-left-radius: calc(0.25rem - 1px);
        border-top-right-radius: calc(0.25rem - 1px);
    }

    .nm_card-img,
    .nm_card-img-bottom {
        border-bottom-right-radius: calc(0.25rem - 1px);
        border-bottom-left-radius: calc(0.25rem - 1px);
    }

    .nm_card-deck .nm_card {
        margin-bottom: 15px;
    }

    @media (min-width: 576px) {
        .nm_card-deck {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-flow: row wrap;
            flex-flow: row wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .nm_card-deck .nm_card {
            -ms-flex: 1 0 0%;
            flex: 1 0 0%;
            margin-right: 15px;
            margin-bottom: 0;
            margin-left: 15px;
        }
    }

    .nm_card-group > .nm_card {
        margin-bottom: 15px;
    }

    @media (min-width: 576px) {
        .nm_card-group {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-flow: row wrap;
            flex-flow: row wrap;
        }
        .nm_card-group > .nm_card {
            -ms-flex: 1 0 0%;
            flex: 1 0 0%;
            margin-bottom: 0;
        }
        .nm_card-group > .nm_card + .nm_card {
            margin-left: 0;
            border-left: 0;
        }
        .nm_card-group > .nm_card:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .nm_card-group > .nm_card:not(:last-child) .nm_card-img-top,
        .nm_card-group > .nm_card:not(:last-child) .nm_card-header {
            border-top-right-radius: 0;
        }
        .nm_card-group > .nm_card:not(:last-child) .nm_card-img-bottom,
        .nm_card-group > .nm_card:not(:last-child) .nm_card-footer {
            border-bottom-right-radius: 0;
        }
        .nm_card-group > .nm_card:not(:first-child) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        .nm_card-group > .nm_card:not(:first-child) .nm_card-img-top,
        .nm_card-group > .nm_card:not(:first-child) .nm_card-header {
            border-top-left-radius: 0;
        }
        .nm_card-group > .nm_card:not(:first-child) .nm_card-img-bottom,
        .nm_card-group > .nm_card:not(:first-child) .nm_card-footer {
            border-bottom-left-radius: 0;
        }
    }

    .nm_card-columns .nm_card {
        margin-bottom: 0.75rem;
    }

    @media (min-width: 576px) {
        .nm_card-columns {
            -webkit-column-count: 3;
            -moz-column-count: 3;
            column-count: 3;
            -webkit-column-gap: 1.25rem;
            -moz-column-gap: 1.25rem;
            column-gap: 1.25rem;
            orphans: 1;
            widows: 1;
        }
        .nm_card-columns .nm_card {
            display: inline-block;
            width: 100%;
        }
    }

    .nm_accordion > .nm_card {
        overflow: hidden;
    }

    .nm_accordion > .nm_card:not(:last-of-type) {
        border-bottom: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .nm_accordion > .nm_card:not(:first-of-type) {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .nm_accordion > .nm_card > .nm_card-header {
        border-radius: 0;
        margin-bottom: -1px;
    }

    .nm_breadcrumb {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        list-style: none;
        background-color: #e9ecef;
        border-radius: 0.25rem;
    }

    .nm_breadcrumb-item + .nm_breadcrumb-item {
        padding-left: 0.5rem;
    }

    .nm_breadcrumb-item + .nm_breadcrumb-item::before {
        display: inline-block;
        padding-right: 0.5rem;
        color: #6c757d;
        content: "/";
    }

    .nm_breadcrumb-item + .nm_breadcrumb-item:hover::before {
        text-decoration: underline;
    }

    .nm_breadcrumb-item + .nm_breadcrumb-item:hover::before {
        text-decoration: none;
    }

    .nm_breadcrumb-item.nm_active {
        color: #6c757d;
    }

    .nm_pagination {
        display: -ms-flexbox;
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 0.25rem;
    }

    .nm_page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #007bff;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

    .nm_page-link:hover {
        z-index: 2;
        color: #0056b3;
        text-decoration: none;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .nm_page-link:focus {
        z-index: 3;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .nm_page-item:first-child .nm_page-link {
        margin-left: 0;
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }

    .nm_page-item:last-child .nm_page-link {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }

    .nm_page-item.nm_active .nm_page-link {
        z-index: 3;
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .nm_page-item.nm_disabled .nm_page-link {
        color: #6c757d;
        pointer-events: none;
        cursor: auto;
        background-color: #fff;
        border-color: #dee2e6;
    }

    .nm_pagination-lg .nm_page-link {
        padding: 0.75rem 1.5rem;
        font-size: 1.25rem;
        line-height: 1.5;
    }

    .nm_pagination-lg .nm_page-item:first-child .nm_page-link {
        border-top-left-radius: 0.3rem;
        border-bottom-left-radius: 0.3rem;
    }

    .nm_pagination-lg .nm_page-item:last-child .nm_page-link {
        border-top-right-radius: 0.3rem;
        border-bottom-right-radius: 0.3rem;
    }

    .nm_pagination-sm .nm_page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .nm_pagination-sm .nm_page-item:first-child .nm_page-link {
        border-top-left-radius: 0.2rem;
        border-bottom-left-radius: 0.2rem;
    }

    .nm_pagination-sm .nm_page-item:last-child .nm_page-link {
        border-top-right-radius: 0.2rem;
        border-bottom-right-radius: 0.2rem;
    }

    .nm_badge {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_badge {
            transition: none;
        }
    }

    a.nm_badge:hover, a.nm_badge:focus {
        text-decoration: none;
    }

    .nm_badge:empty {
        display: none;
    }

    .nm_btn .nm_badge {
        position: relative;
        top: -1px;
    }

    .nm_badge-pill {
        padding-right: 0.6em;
        padding-left: 0.6em;
        border-radius: 10rem;
    }

    .nm_badge-primary {
        color: #fff;
        background-color: #007bff;
    }

    a.nm_badge-primary:hover, a.nm_badge-primary:focus {
        color: #fff;
        background-color: #0062cc;
    }

    a.nm_badge-primary:focus, a.nm_badge-primary.nm_focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5);
    }

    .nm_badge-secondary {
        color: #fff;
        background-color: #6c757d;
    }

    a.nm_badge-secondary:hover, a.nm_badge-secondary:focus {
        color: #fff;
        background-color: #545b62;
    }

    a.nm_badge-secondary:focus, a.nm_badge-secondary.nm_focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5);
    }

    .nm_badge-success {
        color: #fff;
        background-color: #28a745;
    }

    a.nm_badge-success:hover, a.nm_badge-success:focus {
        color: #fff;
        background-color: #1e7e34;
    }

    a.nm_badge-success:focus, a.nm_badge-success.nm_focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5);
    }

    .nm_badge-info {
        color: #fff;
        background-color: #17a2b8;
    }

    a.nm_badge-info:hover, a.nm_badge-info:focus {
        color: #fff;
        background-color: #117a8b;
    }

    a.nm_badge-info:focus, a.nm_badge-info.nm_focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.5);
    }

    .nm_badge-warning {
        color: #212529;
        background-color: #ffc107;
    }

    a.nm_badge-warning:hover, a.nm_badge-warning:focus {
        color: #212529;
        background-color: #d39e00;
    }

    a.nm_badge-warning:focus, a.nm_badge-warning.nm_focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.5);
    }

    .nm_badge-danger {
        color: #fff;
        background-color: #dc3545;
    }

    a.nm_badge-danger:hover, a.nm_badge-danger:focus {
        color: #fff;
        background-color: #bd2130;
    }

    a.nm_badge-danger:focus, a.nm_badge-danger.nm_focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5);
    }

    .nm_badge-light {
        color: #212529;
        background-color: #f8f9fa;
    }

    a.nm_badge-light:hover, a.nm_badge-light:focus {
        color: #212529;
        background-color: #dae0e5;
    }

    a.nm_badge-light:focus, a.nm_badge-light.nm_focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(248, 249, 250, 0.5);
    }

    .nm_badge-dark {
        color: #fff;
        background-color: #343a40;
    }

    a.nm_badge-dark:hover, a.nm_badge-dark:focus {
        color: #fff;
        background-color: #1d2124;
    }

    a.nm_badge-dark:focus, a.nm_badge-dark.nm_focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.5);
    }

    .nm_jumbotron {
        padding: 2rem 1rem;
        margin-bottom: 2rem;
        background-color: #e9ecef;
        border-radius: 0.3rem;
    }

    @media (min-width: 576px) {
        .nm_jumbotron {
            padding: 4rem 2rem;
        }
    }

    .nm_jumbotron-fluid {
        padding-right: 0;
        padding-left: 0;
        border-radius: 0;
    }

    .nm_alert {
        position: relative;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }

    .nm_alert-heading {
        color: inherit;
    }

    .nm_alert-link {
        font-weight: 700;
    }

    .nm_alert-dismissible {
        padding-right: 4rem;
    }

    .nm_alert-dismissible .nm_close {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.75rem 1.25rem;
        color: inherit;
    }

    .nm_alert-primary {
        color: #004085;
        background-color: #cce5ff;
        border-color: #b8daff;
    }

    .nm_alert-primary hr {
        border-top-color: #9fcdff;
    }

    .nm_alert-primary .nm_alert-link {
        color: #002752;
    }

    .nm_alert-secondary {
        color: #383d41;
        background-color: #e2e3e5;
        border-color: #d6d8db;
    }

    .nm_alert-secondary hr {
        border-top-color: #c8cbcf;
    }

    .nm_alert-secondary .nm_alert-link {
        color: #202326;
    }

    .nm_alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .nm_alert-success hr {
        border-top-color: #b1dfbb;
    }

    .nm_alert-success .nm_alert-link {
        color: #0b2e13;
    }

    .nm_alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .nm_alert-info hr {
        border-top-color: #abdde5;
    }

    .nm_alert-info .nm_alert-link {
        color: #062c33;
    }

    .nm_alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }

    .nm_alert-warning hr {
        border-top-color: #ffe8a1;
    }

    .nm_alert-warning .nm_alert-link {
        color: #533f03;
    }

    .nm_alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .nm_alert-danger hr {
        border-top-color: #f1b0b7;
    }

    .nm_alert-danger .nm_alert-link {
        color: #491217;
    }

    .nm_alert-light {
        color: #818182;
        background-color: #fefefe;
        border-color: #fdfdfe;
    }

    .nm_alert-light hr {
        border-top-color: #ececf6;
    }

    .nm_alert-light .nm_alert-link {
        color: #686868;
    }

    .nm_alert-dark {
        color: #1b1e21;
        background-color: #d6d8d9;
        border-color: #c6c8ca;
    }

    .nm_alert-dark hr {
        border-top-color: #b9bbbe;
    }

    .nm_alert-dark .nm_alert-link {
        color: #040505;
    }

    @-webkit-keyframes progress-bar-stripes {
        from {
            background-position: 1rem 0;
        }
        to {
            background-position: 0 0;
        }
    }

    @keyframes progress-bar-stripes {
        from {
            background-position: 1rem 0;
        }
        to {
            background-position: 0 0;
        }
    }

    .nm_progress {
        display: -ms-flexbox;
        display: flex;
        height: 1rem;
        overflow: hidden;
        font-size: 0.75rem;
        background-color: #e9ecef;
        border-radius: 0.25rem;
    }

    .nm_progress-bar {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-pack: center;
        justify-content: center;
        overflow: hidden;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        background-color: #007bff;
        transition: width 0.6s ease;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_progress-bar {
            transition: none;
        }
    }

    .nm_progress-bar-striped {
        background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
        background-size: 1rem 1rem;
    }

    .nm_progress-bar-animated {
        -webkit-animation: progress-bar-stripes 1s linear infinite;
        animation: progress-bar-stripes 1s linear infinite;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_progress-bar-animated {
            -webkit-animation: none;
            animation: none;
        }
    }

    .nm_media {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: start;
        align-items: flex-start;
    }

    .nm_media-body {
        -ms-flex: 1;
        flex: 1;
    }

    .nm_list-group {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        padding-left: 0;
        margin-bottom: 0;
    }

    .nm_list-group-item-action {
        width: 100%;
        color: #495057;
        text-align: inherit;
    }

    .nm_list-group-item-action:hover, .nm_list-group-item-action:focus {
        z-index: 1;
        color: #495057;
        text-decoration: none;
        background-color: #f8f9fa;
    }

    .nm_list-group-item-action:active {
        color: #212529;
        background-color: #e9ecef;
    }

    .nm_list-group-item {
        position: relative;
        display: block;
        padding: 0.75rem 1.25rem;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .nm_list-group-item:first-child {
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
    }

    .nm_list-group-item:last-child {
        border-bottom-right-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }

    .nm_list-group-item.nm_disabled, .nm_list-group-item:disabled {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
    }

    .nm_list-group-item.nm_active {
        z-index: 2;
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .nm_list-group-item + .nm_list-group-item {
        border-top-width: 0;
    }

    .nm_list-group-item + .nm_list-group-item.nm_active {
        margin-top: -1px;
        border-top-width: 1px;
    }

    .nm_list-group-horizontal {
        -ms-flex-direction: row;
        flex-direction: row;
    }

    .nm_list-group-horizontal .nm_list-group-item:first-child {
        border-bottom-left-radius: 0.25rem;
        border-top-right-radius: 0;
    }

    .nm_list-group-horizontal .nm_list-group-item:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-left-radius: 0;
    }

    .nm_list-group-horizontal .nm_list-group-item.nm_active {
        margin-top: 0;
    }

    .nm_list-group-horizontal .nm_list-group-item + .nm_list-group-item {
        border-top-width: 1px;
        border-left-width: 0;
    }

    .nm_list-group-horizontal .nm_list-group-item + .nm_list-group-item.nm_active {
        margin-left: -1px;
        border-left-width: 1px;
    }

    @media (min-width: 576px) {
        .nm_list-group-horizontal-sm {
            -ms-flex-direction: row;
            flex-direction: row;
        }
        .nm_list-group-horizontal-sm .nm_list-group-item:first-child {
            border-bottom-left-radius: 0.25rem;
            border-top-right-radius: 0;
        }
        .nm_list-group-horizontal-sm .nm_list-group-item:last-child {
            border-top-right-radius: 0.25rem;
            border-bottom-left-radius: 0;
        }
        .nm_list-group-horizontal-sm .nm_list-group-item.nm_active {
            margin-top: 0;
        }
        .nm_list-group-horizontal-sm .nm_list-group-item + .nm_list-group-item {
            border-top-width: 1px;
            border-left-width: 0;
        }
        .nm_list-group-horizontal-sm .nm_list-group-item + .nm_list-group-item.nm_active {
            margin-left: -1px;
            border-left-width: 1px;
        }
    }

    @media (min-width: 768px) {
        .nm_list-group-horizontal-md {
            -ms-flex-direction: row;
            flex-direction: row;
        }
        .nm_list-group-horizontal-md .nm_list-group-item:first-child {
            border-bottom-left-radius: 0.25rem;
            border-top-right-radius: 0;
        }
        .nm_list-group-horizontal-md .nm_list-group-item:last-child {
            border-top-right-radius: 0.25rem;
            border-bottom-left-radius: 0;
        }
        .nm_list-group-horizontal-md .nm_list-group-item.nm_active {
            margin-top: 0;
        }
        .nm_list-group-horizontal-md .nm_list-group-item + .nm_list-group-item {
            border-top-width: 1px;
            border-left-width: 0;
        }
        .nm_list-group-horizontal-md .nm_list-group-item + .nm_list-group-item.nm_active {
            margin-left: -1px;
            border-left-width: 1px;
        }
    }

    @media (min-width: 992px) {
        .nm_list-group-horizontal-lg {
            -ms-flex-direction: row;
            flex-direction: row;
        }
        .nm_list-group-horizontal-lg .nm_list-group-item:first-child {
            border-bottom-left-radius: 0.25rem;
            border-top-right-radius: 0;
        }
        .nm_list-group-horizontal-lg .nm_list-group-item:last-child {
            border-top-right-radius: 0.25rem;
            border-bottom-left-radius: 0;
        }
        .nm_list-group-horizontal-lg .nm_list-group-item.nm_active {
            margin-top: 0;
        }
        .nm_list-group-horizontal-lg .nm_list-group-item + .nm_list-group-item {
            border-top-width: 1px;
            border-left-width: 0;
        }
        .nm_list-group-horizontal-lg .nm_list-group-item + .nm_list-group-item.nm_active {
            margin-left: -1px;
            border-left-width: 1px;
        }
    }

    @media (min-width: 1200px) {
        .nm_list-group-horizontal-xl {
            -ms-flex-direction: row;
            flex-direction: row;
        }
        .nm_list-group-horizontal-xl .nm_list-group-item:first-child {
            border-bottom-left-radius: 0.25rem;
            border-top-right-radius: 0;
        }
        .nm_list-group-horizontal-xl .nm_list-group-item:last-child {
            border-top-right-radius: 0.25rem;
            border-bottom-left-radius: 0;
        }
        .nm_list-group-horizontal-xl .nm_list-group-item.nm_active {
            margin-top: 0;
        }
        .nm_list-group-horizontal-xl .nm_list-group-item + .nm_list-group-item {
            border-top-width: 1px;
            border-left-width: 0;
        }
        .nm_list-group-horizontal-xl .nm_list-group-item + .nm_list-group-item.nm_active {
            margin-left: -1px;
            border-left-width: 1px;
        }
    }

    .nm_list-group-flush .nm_list-group-item {
        border-right-width: 0;
        border-left-width: 0;
        border-radius: 0;
    }

    .nm_list-group-flush .nm_list-group-item:first-child {
        border-top-width: 0;
    }

    .nm_list-group-flush:last-child .nm_list-group-item:last-child {
        border-bottom-width: 0;
    }

    .nm_list-group-item-primary {
        color: #004085;
        background-color: #b8daff;
    }

    .nm_list-group-item-primary.nm_list-group-item-action:hover, .nm_list-group-item-primary.nm_list-group-item-action:focus {
        color: #004085;
        background-color: #9fcdff;
    }

    .nm_list-group-item-primary.nm_list-group-item-action.nm_active {
        color: #fff;
        background-color: #004085;
        border-color: #004085;
    }

    .nm_list-group-item-secondary {
        color: #383d41;
        background-color: #d6d8db;
    }

    .nm_list-group-item-secondary.nm_list-group-item-action:hover, .nm_list-group-item-secondary.nm_list-group-item-action:focus {
        color: #383d41;
        background-color: #c8cbcf;
    }

    .nm_list-group-item-secondary.nm_list-group-item-action.nm_active {
        color: #fff;
        background-color: #383d41;
        border-color: #383d41;
    }

    .nm_list-group-item-success {
        color: #155724;
        background-color: #c3e6cb;
    }

    .nm_list-group-item-success.nm_list-group-item-action:hover, .nm_list-group-item-success.nm_list-group-item-action:focus {
        color: #155724;
        background-color: #b1dfbb;
    }

    .nm_list-group-item-success.nm_list-group-item-action.nm_active {
        color: #fff;
        background-color: #155724;
        border-color: #155724;
    }

    .nm_list-group-item-info {
        color: #0c5460;
        background-color: #bee5eb;
    }

    .nm_list-group-item-info.nm_list-group-item-action:hover, .nm_list-group-item-info.nm_list-group-item-action:focus {
        color: #0c5460;
        background-color: #abdde5;
    }

    .nm_list-group-item-info.nm_list-group-item-action.nm_active {
        color: #fff;
        background-color: #0c5460;
        border-color: #0c5460;
    }

    .nm_list-group-item-warning {
        color: #856404;
        background-color: #ffeeba;
    }

    .nm_list-group-item-warning.nm_list-group-item-action:hover, .nm_list-group-item-warning.nm_list-group-item-action:focus {
        color: #856404;
        background-color: #ffe8a1;
    }

    .nm_list-group-item-warning.nm_list-group-item-action.nm_active {
        color: #fff;
        background-color: #856404;
        border-color: #856404;
    }

    .nm_list-group-item-danger {
        color: #721c24;
        background-color: #f5c6cb;
    }

    .nm_list-group-item-danger.nm_list-group-item-action:hover, .nm_list-group-item-danger.nm_list-group-item-action:focus {
        color: #721c24;
        background-color: #f1b0b7;
    }

    .nm_list-group-item-danger.nm_list-group-item-action.nm_active {
        color: #fff;
        background-color: #721c24;
        border-color: #721c24;
    }

    .nm_list-group-item-light {
        color: #818182;
        background-color: #fdfdfe;
    }

    .nm_list-group-item-light.nm_list-group-item-action:hover, .nm_list-group-item-light.nm_list-group-item-action:focus {
        color: #818182;
        background-color: #ececf6;
    }

    .nm_list-group-item-light.nm_list-group-item-action.nm_active {
        color: #fff;
        background-color: #818182;
        border-color: #818182;
    }

    .nm_list-group-item-dark {
        color: #1b1e21;
        background-color: #c6c8ca;
    }

    .nm_list-group-item-dark.nm_list-group-item-action:hover, .nm_list-group-item-dark.nm_list-group-item-action:focus {
        color: #1b1e21;
        background-color: #b9bbbe;
    }

    .nm_list-group-item-dark.nm_list-group-item-action.nm_active {
        color: #fff;
        background-color: #1b1e21;
        border-color: #1b1e21;
    }

    .nm_close {
        float: right;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
    }

    .nm_close:hover {
        color: #000;
        text-decoration: none;
    }

    .nm_close:not(:disabled):not(.nm_disabled):hover, .nm_close:not(:disabled):not(.nm_disabled):focus {
        opacity: .75;
    }

    button.nm_close {
        padding: 0;
        background-color: transparent;
        border: 0;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    a.nm_close.nm_disabled {
        pointer-events: none;
    }

    .nm_toast {
        max-width: 350px;
        overflow: hidden;
        font-size: 0.875rem;
        background-color: rgba(255, 255, 255, 0.85);
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
        opacity: 0;
        border-radius: 0.25rem;
    }

    .nm_toast:not(:last-child) {
        margin-bottom: 0.75rem;
    }

    .nm_toast.showing {
        opacity: 1;
    }

    .nm_toast.show {
        display: block;
        opacity: 1;
    }

    .nm_toast.nm_hide {
        display: none;
    }

    .nm_toast-header {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding: 0.25rem 0.75rem;
        color: #6c757d;
        background-color: rgba(255, 255, 255, 0.85);
        background-clip: padding-box;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .nm_toast-body {
        padding: 0.75rem;
    }

    .nm_modal-open {
        overflow: hidden;
    }

    .nm_modal-open .nm_modal {
        overflow-x: hidden;
        overflow-y: auto;
    }

    .nm_modal {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        display: none;
        width: 100%;
        height: 100%;
        overflow: hidden;
        outline: 0;
    }

    .nm_modal-dialog {
        position: relative;
        width: auto;
        margin: 0.5rem;
        pointer-events: none;
    }

    .nm_modal.nm_fade .nm_modal-dialog {
        transition: -webkit-transform 0.3s ease-out;
        transition: transform 0.3s ease-out;
        transition: transform 0.3s ease-out, -webkit-transform 0.3s ease-out;
        -webkit-transform: translate(0, -50px);
        transform: translate(0, -50px);
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_modal.nm_fade .nm_modal-dialog {
            transition: none;
        }
    }

    .nm_modal.show .nm_modal-dialog {
        -webkit-transform: none;
        transform: none;
    }

    .nm_modal.nm_modal-static .nm_modal-dialog {
        -webkit-transform: scale(1.02);
        transform: scale(1.02);
    }

    .nm_modal-dialog-scrollable {
        display: -ms-flexbox;
        display: flex;
        max-height: calc(100% - 1rem);
    }

    .nm_modal-dialog-scrollable .nm_modal-content {
        max-height: calc(100vh - 1rem);
        overflow: hidden;
    }

    .nm_modal-dialog-scrollable .nm_modal-header,
    .nm_modal-dialog-scrollable .nm_modal-footer {
        -ms-flex-negative: 0;
        flex-shrink: 0;
    }

    .nm_modal-dialog-scrollable .nm_modal-body {
        overflow-y: auto;
    }

    .nm_modal-dialog-centered {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        min-height: calc(100% - 1rem);
    }

    .nm_modal-dialog-centered::before {
        display: block;
        height: calc(100vh - 1rem);
        content: "";
    }

    .nm_modal-dialog-centered.nm_modal-dialog-scrollable {
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-pack: center;
        justify-content: center;
        height: 100%;
    }

    .nm_modal-dialog-centered.nm_modal-dialog-scrollable .nm_modal-content {
        max-height: none;
    }

    .nm_modal-dialog-centered.nm_modal-dialog-scrollable::before {
        content: none;
    }

    .nm_modal-content {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        width: 100%;
        pointer-events: auto;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 0.3rem;
        outline: 0;
    }

    .nm_modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 100vw;
        height: 100vh;
        background-color: #000;
    }

    .nm_modal-backdrop.nm_fade {
        opacity: 0;
    }

    .nm_modal-backdrop.show {
        opacity: 0.5;
    }

    .nm_modal-header {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: start;
        align-items: flex-start;
        -ms-flex-pack: justify;
        justify-content: space-between;
        padding: 1rem 1rem;
        border-bottom: 1px solid #dee2e6;
        border-top-left-radius: calc(0.3rem - 1px);
        border-top-right-radius: calc(0.3rem - 1px);
    }

    .nm_modal-header .nm_close {
        padding: 1rem 1rem;
        margin: -1rem -1rem -1rem auto;
    }

    .nm_modal-title {
        margin-bottom: 0;
        line-height: 1.5;
    }

    .nm_modal-body {
        position: relative;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1rem;
    }

    .nm_modal-footer {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-pack: end;
        justify-content: flex-end;
        padding: 0.75rem;
        border-top: 1px solid #dee2e6;
        border-bottom-right-radius: calc(0.3rem - 1px);
        border-bottom-left-radius: calc(0.3rem - 1px);
    }

    .nm_modal-footer > * {
        margin: 0.25rem;
    }

    .nm_modal-scrollbar-measure {
        position: absolute;
        top: -9999px;
        width: 50px;
        height: 50px;
        overflow: scroll;
    }

    @media (min-width: 576px) {
        .nm_modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }
        .nm_modal-dialog-scrollable {
            max-height: calc(100% - 3.5rem);
        }
        .nm_modal-dialog-scrollable .nm_modal-content {
            max-height: calc(100vh - 3.5rem);
        }
        .nm_modal-dialog-centered {
            min-height: calc(100% - 3.5rem);
        }
        .nm_modal-dialog-centered::before {
            height: calc(100vh - 3.5rem);
        }
        .nm_modal-sm {
            max-width: 300px;
        }
    }

    @media (min-width: 992px) {
        .nm_modal-lg,
        .nm_modal-xl {
            max-width: 1200px;
        }
    }

    @media (min-width: 1200px) {
        .nm_modal-xl {
            max-width: 1200px;
        }
    }

    .nm_tooltip {
        position: absolute;
        z-index: 1070;
        display: block;
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        font-style: normal;
        font-weight: 400;
        line-height: 1.5;
        text-align: left;
        text-align: start;
        text-decoration: none;
        text-shadow: none;
        text-transform: none;
        letter-spacing: normal;
        word-break: normal;
        word-spacing: normal;
        white-space: normal;
        line-break: auto;
        font-size: 0.875rem;
        word-wrap: break-word;
        opacity: 0;
    }

    .nm_tooltip.show {
        opacity: 0.9;
    }

    .nm_tooltip .nm_arrow {
        position: absolute;
        display: block;
        width: 0.8rem;
        height: 0.4rem;
    }

    .nm_tooltip .nm_arrow::before {
        position: absolute;
        content: "";
        border-color: transparent;
        border-style: solid;
    }

    .nm_bs-tooltip-top, .nm_bs-tooltip-auto[x-placement^="top"] {
        padding: 0.4rem 0;
    }

    .nm_bs-tooltip-top .nm_arrow, .nm_bs-tooltip-auto[x-placement^="top"] .nm_arrow {
        bottom: 0;
    }

    .nm_bs-tooltip-top .nm_arrow::before, .nm_bs-tooltip-auto[x-placement^="top"] .nm_arrow::before {
        top: 0;
        border-width: 0.4rem 0.4rem 0;
        border-top-color: #000;
    }

    .nm_bs-tooltip-right, .nm_bs-tooltip-auto[x-placement^="right"] {
        padding: 0 0.4rem;
    }

    .nm_bs-tooltip-right .nm_arrow, .nm_bs-tooltip-auto[x-placement^="right"] .nm_arrow {
        left: 0;
        width: 0.4rem;
        height: 0.8rem;
    }

    .nm_bs-tooltip-right .nm_arrow::before, .nm_bs-tooltip-auto[x-placement^="right"] .nm_arrow::before {
        right: 0;
        border-width: 0.4rem 0.4rem 0.4rem 0;
        border-right-color: #000;
    }

    .nm_bs-tooltip-bottom, .nm_bs-tooltip-auto[x-placement^="bottom"] {
        padding: 0.4rem 0;
    }

    .nm_bs-tooltip-bottom .nm_arrow, .nm_bs-tooltip-auto[x-placement^="bottom"] .nm_arrow {
        top: 0;
    }

    .nm_bs-tooltip-bottom .nm_arrow::before, .nm_bs-tooltip-auto[x-placement^="bottom"] .nm_arrow::before {
        bottom: 0;
        border-width: 0 0.4rem 0.4rem;
        border-bottom-color: #000;
    }

    .nm_bs-tooltip-left, .nm_bs-tooltip-auto[x-placement^="left"] {
        padding: 0 0.4rem;
    }

    .nm_bs-tooltip-left .nm_arrow, .nm_bs-tooltip-auto[x-placement^="left"] .nm_arrow {
        right: 0;
        width: 0.4rem;
        height: 0.8rem;
    }

    .nm_bs-tooltip-left .nm_arrow::before, .nm_bs-tooltip-auto[x-placement^="left"] .nm_arrow::before {
        left: 0;
        border-width: 0.4rem 0 0.4rem 0.4rem;
        border-left-color: #000;
    }

    .nm_tooltip-inner {
        max-width: 200px;
        padding: 0.25rem 0.5rem;
        color: #fff;
        text-align: center;
        background-color: #000;
        border-radius: 0.25rem;
    }

    .nm_popover {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1060;
        display: block;
        max-width: 276px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        font-style: normal;
        font-weight: 400;
        line-height: 1.5;
        text-align: left;
        text-align: start;
        text-decoration: none;
        text-shadow: none;
        text-transform: none;
        letter-spacing: normal;
        word-break: normal;
        word-spacing: normal;
        white-space: normal;
        line-break: auto;
        font-size: 0.875rem;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 0.3rem;
    }

    .nm_popover .nm_arrow {
        position: absolute;
        display: block;
        width: 1rem;
        height: 0.5rem;
        margin: 0 0.3rem;
    }

    .nm_popover .nm_arrow::before, .nm_popover .nm_arrow::after {
        position: absolute;
        display: block;
        content: "";
        border-color: transparent;
        border-style: solid;
    }

    .nm_bs-popover-top, .nm_bs-popover-auto[x-placement^="top"] {
        margin-bottom: 0.5rem;
    }

    .nm_bs-popover-top > .nm_arrow, .nm_bs-popover-auto[x-placement^="top"] > .nm_arrow {
        bottom: calc(-0.5rem - 1px);
    }

    .nm_bs-popover-top > .nm_arrow::before, .nm_bs-popover-auto[x-placement^="top"] > .nm_arrow::before {
        bottom: 0;
        border-width: 0.5rem 0.5rem 0;
        border-top-color: rgba(0, 0, 0, 0.25);
    }

    .nm_bs-popover-top > .nm_arrow::after, .nm_bs-popover-auto[x-placement^="top"] > .nm_arrow::after {
        bottom: 1px;
        border-width: 0.5rem 0.5rem 0;
        border-top-color: #fff;
    }

    .nm_bs-popover-right, .nm_bs-popover-auto[x-placement^="right"] {
        margin-left: 0.5rem;
    }

    .nm_bs-popover-right > .nm_arrow, .nm_bs-popover-auto[x-placement^="right"] > .nm_arrow {
        left: calc(-0.5rem - 1px);
        width: 0.5rem;
        height: 1rem;
        margin: 0.3rem 0;
    }

    .nm_bs-popover-right > .nm_arrow::before, .nm_bs-popover-auto[x-placement^="right"] > .nm_arrow::before {
        left: 0;
        border-width: 0.5rem 0.5rem 0.5rem 0;
        border-right-color: rgba(0, 0, 0, 0.25);
    }

    .nm_bs-popover-right > .nm_arrow::after, .nm_bs-popover-auto[x-placement^="right"] > .nm_arrow::after {
        left: 1px;
        border-width: 0.5rem 0.5rem 0.5rem 0;
        border-right-color: #fff;
    }

    .nm_bs-popover-bottom, .nm_bs-popover-auto[x-placement^="bottom"] {
        margin-top: 0.5rem;
    }

    .nm_bs-popover-bottom > .nm_arrow, .nm_bs-popover-auto[x-placement^="bottom"] > .nm_arrow {
        top: calc(-0.5rem - 1px);
    }

    .nm_bs-popover-bottom > .nm_arrow::before, .nm_bs-popover-auto[x-placement^="bottom"] > .nm_arrow::before {
        top: 0;
        border-width: 0 0.5rem 0.5rem 0.5rem;
        border-bottom-color: rgba(0, 0, 0, 0.25);
    }

    .nm_bs-popover-bottom > .nm_arrow::after, .nm_bs-popover-auto[x-placement^="bottom"] > .nm_arrow::after {
        top: 1px;
        border-width: 0 0.5rem 0.5rem 0.5rem;
        border-bottom-color: #fff;
    }

    .nm_bs-popover-bottom .nm_popover-header::before, .nm_bs-popover-auto[x-placement^="bottom"] .nm_popover-header::before {
        position: absolute;
        top: 0;
        left: 50%;
        display: block;
        width: 1rem;
        margin-left: -0.5rem;
        content: "";
        border-bottom: 1px solid #f7f7f7;
    }

    .nm_bs-popover-left, .nm_bs-popover-auto[x-placement^="left"] {
        margin-right: 0.5rem;
    }

    .nm_bs-popover-left > .nm_arrow, .nm_bs-popover-auto[x-placement^="left"] > .nm_arrow {
        right: calc(-0.5rem - 1px);
        width: 0.5rem;
        height: 1rem;
        margin: 0.3rem 0;
    }

    .nm_bs-popover-left > .nm_arrow::before, .nm_bs-popover-auto[x-placement^="left"] > .nm_arrow::before {
        right: 0;
        border-width: 0.5rem 0 0.5rem 0.5rem;
        border-left-color: rgba(0, 0, 0, 0.25);
    }

    .nm_bs-popover-left > .nm_arrow::after, .nm_bs-popover-auto[x-placement^="left"] > .nm_arrow::after {
        right: 1px;
        border-width: 0.5rem 0 0.5rem 0.5rem;
        border-left-color: #fff;
    }

    .nm_popover-header {
        padding: 0.5rem 0.75rem;
        margin-bottom: 0;
        font-size: 1rem;
        background-color: #f7f7f7;
        border-bottom: 1px solid #ebebeb;
        border-top-left-radius: calc(0.3rem - 1px);
        border-top-right-radius: calc(0.3rem - 1px);
    }

    .nm_popover-header:empty {
        display: none;
    }

    .nm_popover-body {
        padding: 0.5rem 0.75rem;
        color: #212529;
    }

    .nm_carousel {
        position: relative;
    }

    .nm_carousel.nm_pointer-event {
        -ms-touch-action: pan-y;
        touch-action: pan-y;
    }

    .nm_carousel-inner {
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .nm_carousel-inner::after {
        display: block;
        clear: both;
        content: "";
    }

    .nm_carousel-item {
        position: relative;
        display: none;
        float: left;
        width: 100%;
        margin-right: -100%;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        transition: -webkit-transform 0.6s ease-in-out;
        transition: transform 0.6s ease-in-out;
        transition: transform 0.6s ease-in-out, -webkit-transform 0.6s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_carousel-item {
            transition: none;
        }
    }

    .nm_carousel-item.nm_active,
    .nm_carousel-item-next,
    .nm_carousel-item-prev {
        display: block;
    }

    .nm_carousel-item-next:not(.nm_carousel-item-left),
    .nm_active.nm_carousel-item-right {
        -webkit-transform: translateX(100%);
        transform: translateX(100%);
    }

    .nm_carousel-item-prev:not(.nm_carousel-item-right),
    .nm_active.nm_carousel-item-left {
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
    }

    .nm_carousel-fade .nm_carousel-item {
        opacity: 0;
        transition-property: opacity;
        -webkit-transform: none;
        transform: none;
    }

    .nm_carousel-fade .nm_carousel-item.nm_active,
    .nm_carousel-fade .nm_carousel-item-next.nm_carousel-item-left,
    .nm_carousel-fade .nm_carousel-item-prev.nm_carousel-item-right {
        z-index: 1;
        opacity: 1;
    }

    .nm_carousel-fade .nm_active.nm_carousel-item-left,
    .nm_carousel-fade .nm_active.nm_carousel-item-right {
        z-index: 0;
        opacity: 0;
        transition: opacity 0s 0.6s;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_carousel-fade .nm_active.nm_carousel-item-left,
        .nm_carousel-fade .nm_active.nm_carousel-item-right {
            transition: none;
        }
    }

    .nm_carousel-control-prev,
    .nm_carousel-control-next {
        position: absolute;
        top: 0;
        bottom: 0;
        z-index: 1;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-pack: center;
        justify-content: center;
        width: 15%;
        color: #fff;
        text-align: center;
        opacity: 0.5;
        transition: opacity 0.15s ease;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_carousel-control-prev,
        .nm_carousel-control-next {
            transition: none;
        }
    }

    .nm_carousel-control-prev:hover, .nm_carousel-control-prev:focus,
    .nm_carousel-control-next:hover,
    .nm_carousel-control-next:focus {
        color: #fff;
        text-decoration: none;
        outline: 0;
        opacity: 0.9;
    }

    .nm_carousel-control-prev {
        left: 0;
    }

    .nm_carousel-control-next {
        right: 0;
    }

    .nm_carousel-control-prev-icon,
    .nm_carousel-control-next-icon {
        display: inline-block;
        width: 20px;
        height: 20px;
        background: no-repeat 50% / 100% 100%;
    }

    .nm_carousel-control-prev-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' fill='%23fff' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath d='M5.25 0l-4 4 4 4 1.5-1.5L4.25 4l2.5-2.5L5.25 0z'/%3e%3c/svg%3e");
    }

    .nm_carousel-control-next-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.nm_w3.nm_org/2000/svg' fill='%23fff' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath d='M2.75 0l-1.5 1.5L3.75 4l-2.5 2.5L2.75 8l4-4-4-4z'/%3e%3c/svg%3e");
    }

    .nm_carousel-indicators {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 15;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-pack: center;
        justify-content: center;
        padding-left: 0;
        margin-right: 15%;
        margin-left: 15%;
        list-style: none;
    }

    .nm_carousel-indicators li {
        box-sizing: content-box;
        -ms-flex: 0 1 auto;
        flex: 0 1 auto;
        width: 30px;
        height: 3px;
        margin-right: 3px;
        margin-left: 3px;
        text-indent: -999px;
        cursor: pointer;
        background-color: #fff;
        background-clip: padding-box;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        opacity: .5;
        transition: opacity 0.6s ease;
    }

    @media (prefers-reduced-motion: reduce) {
        .nm_carousel-indicators li {
            transition: none;
        }
    }

    .nm_carousel-indicators .nm_active {
        opacity: 1;
    }

    .nm_carousel-caption {
        position: absolute;
        right: 15%;
        bottom: 20px;
        left: 15%;
        z-index: 10;
        padding-top: 20px;
        padding-bottom: 20px;
        color: #fff;
        text-align: center;
    }

    @-webkit-keyframes spinner-border {
        to {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes spinner-border {
        to {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    .nm_spinner-border {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        vertical-align: text-bottom;
        border: 0.25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        -webkit-animation: spinner-border .75s linear infinite;
        animation: spinner-border .75s linear infinite;
    }

    .nm_spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }

    @-webkit-keyframes spinner-grow {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
        }
        50% {
            opacity: 1;
        }
    }

    @keyframes spinner-grow {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
        }
        50% {
            opacity: 1;
        }
    }

    .nm_spinner-grow {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        vertical-align: text-bottom;
        background-color: currentColor;
        border-radius: 50%;
        opacity: 0;
        -webkit-animation: spinner-grow .75s linear infinite;
        animation: spinner-grow .75s linear infinite;
    }

    .nm_spinner-grow-sm {
        width: 1rem;
        height: 1rem;
    }

    .nm_align-baseline {
        vertical-align: baseline !important;
    }

    .nm_align-top {
        vertical-align: top !important;
    }

    .nm_align-middle {
        vertical-align: middle !important;
    }

    .nm_align-bottom {
        vertical-align: bottom !important;
    }

    .nm_align-text-bottom {
        vertical-align: text-bottom !important;
    }

    .nm_align-text-top {
        vertical-align: text-top !important;
    }

    .nm_bg-primary {
        background-color: #007bff !important;
    }

    a.nm_bg-primary:hover, a.nm_bg-primary:focus,
    button.nm_bg-primary:hover,
    button.nm_bg-primary:focus {
        background-color: #0062cc !important;
    }

    .nm_bg-secondary {
        background-color: #6c757d !important;
    }

    a.nm_bg-secondary:hover, a.nm_bg-secondary:focus,
    button.nm_bg-secondary:hover,
    button.nm_bg-secondary:focus {
        background-color: #545b62 !important;
    }

    .nm_bg-success {
        background-color: #28a745 !important;
    }

    a.nm_bg-success:hover, a.nm_bg-success:focus,
    button.nm_bg-success:hover,
    button.nm_bg-success:focus {
        background-color: #1e7e34 !important;
    }

    .nm_bg-info {
        background-color: #17a2b8 !important;
    }

    a.nm_bg-info:hover, a.nm_bg-info:focus,
    button.nm_bg-info:hover,
    button.nm_bg-info:focus {
        background-color: #117a8b !important;
    }

    .nm_bg-warning {
        background-color: #ffc107 !important;
    }

    a.nm_bg-warning:hover, a.nm_bg-warning:focus,
    button.nm_bg-warning:hover,
    button.nm_bg-warning:focus {
        background-color: #d39e00 !important;
    }

    .nm_bg-danger {
        background-color: #dc3545 !important;
    }

    a.nm_bg-danger:hover, a.nm_bg-danger:focus,
    button.nm_bg-danger:hover,
    button.nm_bg-danger:focus {
        background-color: #bd2130 !important;
    }

    .nm_bg-light {
        background-color: #f8f9fa !important;
    }

    a.nm_bg-light:hover, a.nm_bg-light:focus,
    button.nm_bg-light:hover,
    button.nm_bg-light:focus {
        background-color: #dae0e5 !important;
    }

    .nm_bg-dark {
        background-color: #343a40 !important;
    }

    a.nm_bg-dark:hover, a.nm_bg-dark:focus,
    button.nm_bg-dark:hover,
    button.nm_bg-dark:focus {
        background-color: #1d2124 !important;
    }

    .nm_bg-white {
        background-color: #fff !important;
    }

    .nm_bg-transparent {
        background-color: transparent !important;
    }

    .nm_border {
        border: 1px solid #dee2e6 !important;
    }

    .nm_border-top {
        border-top: 1px solid #dee2e6 !important;
    }

    .nm_border-right {
        border-right: 1px solid #dee2e6 !important;
    }

    .nm_border-bottom {
        border-bottom: 1px solid #dee2e6 !important;
    }

    .nm_border-left {
        border-left: 1px solid #dee2e6 !important;
    }

    .nm_border-0 {
        border: 0 !important;
    }

    .nm_border-top-0 {
        border-top: 0 !important;
    }

    .nm_border-right-0 {
        border-right: 0 !important;
    }

    .nm_border-bottom-0 {
        border-bottom: 0 !important;
    }

    .nm_border-left-0 {
        border-left: 0 !important;
    }

    .nm_border-primary {
        border-color: #007bff !important;
    }

    .nm_border-secondary {
        border-color: #6c757d !important;
    }

    .nm_border-success {
        border-color: #28a745 !important;
    }

    .nm_border-info {
        border-color: #17a2b8 !important;
    }

    .nm_border-warning {
        border-color: #ffc107 !important;
    }

    .nm_border-danger {
        border-color: #dc3545 !important;
    }

    .nm_border-light {
        border-color: #f8f9fa !important;
    }

    .nm_border-dark {
        border-color: #343a40 !important;
    }

    .nm_border-white {
        border-color: #fff !important;
    }

    .nm_rounded-sm {
        border-radius: 0.2rem !important;
    }

    .nm_rounded {
        border-radius: 0.25rem !important;
    }

    .nm_rounded-top {
        border-top-left-radius: 0.25rem !important;
        border-top-right-radius: 0.25rem !important;
    }

    .nm_rounded-right {
        border-top-right-radius: 0.25rem !important;
        border-bottom-right-radius: 0.25rem !important;
    }

    .nm_rounded-bottom {
        border-bottom-right-radius: 0.25rem !important;
        border-bottom-left-radius: 0.25rem !important;
    }

    .nm_rounded-left {
        border-top-left-radius: 0.25rem !important;
        border-bottom-left-radius: 0.25rem !important;
    }

    .nm_rounded-lg {
        border-radius: 0.3rem !important;
    }

    .nm_rounded-circle {
        border-radius: 50% !important;
    }

    .nm_rounded-pill {
        border-radius: 50rem !important;
    }

    .nm_rounded-0 {
        border-radius: 0 !important;
    }

    .nm_clearfix::after {
        display: block;
        clear: both;
        content: "";
    }

    .nm_d-none {
        display: none !important;
    }

    .nm_d-inline {
        display: inline !important;
    }

    .nm_d-inline-block {
        display: inline-block !important;
    }

    .nm_d-block {
        display: block !important;
    }

    .nm_d-table {
        display: table !important;
    }

    .nm_d-table-row {
        display: table-row !important;
    }

    .nm_d-table-cell {
        display: table-cell !important;
    }

    .nm_d-flex {
        display: -ms-flexbox !important;
        display: flex !important;
    }

    .nm_d-inline-flex {
        display: -ms-inline-flexbox !important;
        display: inline-flex !important;
    }

    @media (min-width: 576px) {
        .nm_d-sm-none {
            display: none !important;
        }
        .nm_d-sm-inline {
            display: inline !important;
        }
        .nm_d-sm-inline-block {
            display: inline-block !important;
        }
        .nm_d-sm-block {
            display: block !important;
        }
        .nm_d-sm-table {
            display: table !important;
        }
        .nm_d-sm-table-row {
            display: table-row !important;
        }
        .nm_d-sm-table-cell {
            display: table-cell !important;
        }
        .nm_d-sm-flex {
            display: -ms-flexbox !important;
            display: flex !important;
        }
        .nm_d-sm-inline-flex {
            display: -ms-inline-flexbox !important;
            display: inline-flex !important;
        }
    }

    @media (min-width: 768px) {
        .nm_d-md-none {
            display: none !important;
        }
        .nm_d-md-inline {
            display: inline !important;
        }
        .nm_d-md-inline-block {
            display: inline-block !important;
        }
        .nm_d-md-block {
            display: block !important;
        }
        .nm_d-md-table {
            display: table !important;
        }
        .nm_d-md-table-row {
            display: table-row !important;
        }
        .nm_d-md-table-cell {
            display: table-cell !important;
        }
        .nm_d-md-flex {
            display: -ms-flexbox !important;
            display: flex !important;
        }
        .nm_d-md-inline-flex {
            display: -ms-inline-flexbox !important;
            display: inline-flex !important;
        }
    }

    @media (min-width: 992px) {
        .nm_d-lg-none {
            display: none !important;
        }
        .nm_d-lg-inline {
            display: inline !important;
        }
        .nm_d-lg-inline-block {
            display: inline-block !important;
        }
        .nm_d-lg-block {
            display: block !important;
        }
        .nm_d-lg-table {
            display: table !important;
        }
        .nm_d-lg-table-row {
            display: table-row !important;
        }
        .nm_d-lg-table-cell {
            display: table-cell !important;
        }
        .nm_d-lg-flex {
            display: -ms-flexbox !important;
            display: flex !important;
        }
        .nm_d-lg-inline-flex {
            display: -ms-inline-flexbox !important;
            display: inline-flex !important;
        }
    }

    @media (min-width: 1200px) {
        .nm_d-xl-none {
            display: none !important;
        }
        .nm_d-xl-inline {
            display: inline !important;
        }
        .nm_d-xl-inline-block {
            display: inline-block !important;
        }
        .nm_d-xl-block {
            display: block !important;
        }
        .nm_d-xl-table {
            display: table !important;
        }
        .nm_d-xl-table-row {
            display: table-row !important;
        }
        .nm_d-xl-table-cell {
            display: table-cell !important;
        }
        .nm_d-xl-flex {
            display: -ms-flexbox !important;
            display: flex !important;
        }
        .nm_d-xl-inline-flex {
            display: -ms-inline-flexbox !important;
            display: inline-flex !important;
        }
    }

    @media print {
        .nm_d-print-none {
            display: none !important;
        }
        .nm_d-print-inline {
            display: inline !important;
        }
        .nm_d-print-inline-block {
            display: inline-block !important;
        }
        .nm_d-print-block {
            display: block !important;
        }
        .nm_d-print-table {
            display: table !important;
        }
        .nm_d-print-table-row {
            display: table-row !important;
        }
        .nm_d-print-table-cell {
            display: table-cell !important;
        }
        .nm_d-print-flex {
            display: -ms-flexbox !important;
            display: flex !important;
        }
        .nm_d-print-inline-flex {
            display: -ms-inline-flexbox !important;
            display: inline-flex !important;
        }
    }

    .nm_embed-responsive {
        position: relative;
        display: block;
        width: 100%;
        padding: 0;
        overflow: hidden;
    }

    .nm_embed-responsive::before {
        display: block;
        content: "";
    }

    .nm_embed-responsive .nm_embed-responsive-item,
    .nm_embed-responsive iframe,
    .nm_embed-responsive embed,
    .nm_embed-responsive object,
    .nm_embed-responsive video {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .nm_embed-responsive-21by9::before {
        padding-top: 42.857143%;
    }

    .nm_embed-responsive-16by9::before {
        padding-top: 56.25%;
    }

    .nm_embed-responsive-4by3::before {
        padding-top: 75%;
    }

    .nm_embed-responsive-1by1::before {
        padding-top: 100%;
    }

    .nm_flex-row {
        -ms-flex-direction: row !important;
        flex-direction: row !important;
    }

    .nm_flex-column {
        -ms-flex-direction: column !important;
        flex-direction: column !important;
    }

    .nm_flex-row-reverse {
        -ms-flex-direction: row-reverse !important;
        flex-direction: row-reverse !important;
    }

    .nm_flex-column-reverse {
        -ms-flex-direction: column-reverse !important;
        flex-direction: column-reverse !important;
    }

    .nm_flex-wrap {
        -ms-flex-wrap: wrap !important;
        flex-wrap: wrap !important;
    }

    .nm_flex-nowrap {
        -ms-flex-wrap: nowrap !important;
        flex-wrap: nowrap !important;
    }

    .nm_flex-wrap-reverse {
        -ms-flex-wrap: wrap-reverse !important;
        flex-wrap: wrap-reverse !important;
    }

    .nm_flex-fill {
        -ms-flex: 1 1 auto !important;
        flex: 1 1 auto !important;
    }

    .nm_flex-grow-0 {
        -ms-flex-positive: 0 !important;
        flex-grow: 0 !important;
    }

    .nm_flex-grow-1 {
        -ms-flex-positive: 1 !important;
        flex-grow: 1 !important;
    }

    .nm_flex-shrink-0 {
        -ms-flex-negative: 0 !important;
        flex-shrink: 0 !important;
    }

    .nm_flex-shrink-1 {
        -ms-flex-negative: 1 !important;
        flex-shrink: 1 !important;
    }

    .nm_justify-content-start {
        -ms-flex-pack: start !important;
        justify-content: flex-start !important;
    }

    .nm_justify-content-end {
        -ms-flex-pack: end !important;
        justify-content: flex-end !important;
    }

    .nm_justify-content-center {
        -ms-flex-pack: center !important;
        justify-content: center !important;
    }

    .nm_justify-content-between {
        -ms-flex-pack: justify !important;
        justify-content: space-between !important;
    }

    .nm_justify-content-around {
        -ms-flex-pack: distribute !important;
        justify-content: space-around !important;
    }

    .nm_align-items-start {
        -ms-flex-align: start !important;
        align-items: flex-start !important;
    }

    .nm_align-items-end {
        -ms-flex-align: end !important;
        align-items: flex-end !important;
    }

    .nm_align-items-center {
        -ms-flex-align: center !important;
        align-items: center !important;
    }

    .nm_align-items-baseline {
        -ms-flex-align: baseline !important;
        align-items: baseline !important;
    }

    .nm_align-items-stretch {
        -ms-flex-align: stretch !important;
        align-items: stretch !important;
    }

    .nm_align-content-start {
        -ms-flex-line-pack: start !important;
        align-content: flex-start !important;
    }

    .nm_align-content-end {
        -ms-flex-line-pack: end !important;
        align-content: flex-end !important;
    }

    .nm_align-content-center {
        -ms-flex-line-pack: center !important;
        align-content: center !important;
    }

    .nm_align-content-between {
        -ms-flex-line-pack: justify !important;
        align-content: space-between !important;
    }

    .nm_align-content-around {
        -ms-flex-line-pack: distribute !important;
        align-content: space-around !important;
    }

    .nm_align-content-stretch {
        -ms-flex-line-pack: stretch !important;
        align-content: stretch !important;
    }

    .nm_align-self-auto {
        -ms-flex-item-align: auto !important;
        align-self: auto !important;
    }

    .nm_align-self-start {
        -ms-flex-item-align: start !important;
        align-self: flex-start !important;
    }

    .nm_align-self-end {
        -ms-flex-item-align: end !important;
        align-self: flex-end !important;
    }

    .nm_align-self-center {
        -ms-flex-item-align: center !important;
        align-self: center !important;
    }

    .nm_align-self-baseline {
        -ms-flex-item-align: baseline !important;
        align-self: baseline !important;
    }

    .nm_align-self-stretch {
        -ms-flex-item-align: stretch !important;
        align-self: stretch !important;
    }

    @media (min-width: 576px) {
        .nm_flex-sm-row {
            -ms-flex-direction: row !important;
            flex-direction: row !important;
        }
        .nm_flex-sm-column {
            -ms-flex-direction: column !important;
            flex-direction: column !important;
        }
        .nm_flex-sm-row-reverse {
            -ms-flex-direction: row-reverse !important;
            flex-direction: row-reverse !important;
        }
        .nm_flex-sm-column-reverse {
            -ms-flex-direction: column-reverse !important;
            flex-direction: column-reverse !important;
        }
        .nm_flex-sm-wrap {
            -ms-flex-wrap: wrap !important;
            flex-wrap: wrap !important;
        }
        .nm_flex-sm-nowrap {
            -ms-flex-wrap: nowrap !important;
            flex-wrap: nowrap !important;
        }
        .nm_flex-sm-wrap-reverse {
            -ms-flex-wrap: wrap-reverse !important;
            flex-wrap: wrap-reverse !important;
        }
        .nm_flex-sm-fill {
            -ms-flex: 1 1 auto !important;
            flex: 1 1 auto !important;
        }
        .nm_flex-sm-grow-0 {
            -ms-flex-positive: 0 !important;
            flex-grow: 0 !important;
        }
        .nm_flex-sm-grow-1 {
            -ms-flex-positive: 1 !important;
            flex-grow: 1 !important;
        }
        .nm_flex-sm-shrink-0 {
            -ms-flex-negative: 0 !important;
            flex-shrink: 0 !important;
        }
        .nm_flex-sm-shrink-1 {
            -ms-flex-negative: 1 !important;
            flex-shrink: 1 !important;
        }
        .nm_justify-content-sm-start {
            -ms-flex-pack: start !important;
            justify-content: flex-start !important;
        }
        .nm_justify-content-sm-end {
            -ms-flex-pack: end !important;
            justify-content: flex-end !important;
        }
        .nm_justify-content-sm-center {
            -ms-flex-pack: center !important;
            justify-content: center !important;
        }
        .nm_justify-content-sm-between {
            -ms-flex-pack: justify !important;
            justify-content: space-between !important;
        }
        .nm_justify-content-sm-around {
            -ms-flex-pack: distribute !important;
            justify-content: space-around !important;
        }
        .nm_align-items-sm-start {
            -ms-flex-align: start !important;
            align-items: flex-start !important;
        }
        .nm_align-items-sm-end {
            -ms-flex-align: end !important;
            align-items: flex-end !important;
        }
        .nm_align-items-sm-center {
            -ms-flex-align: center !important;
            align-items: center !important;
        }
        .nm_align-items-sm-baseline {
            -ms-flex-align: baseline !important;
            align-items: baseline !important;
        }
        .nm_align-items-sm-stretch {
            -ms-flex-align: stretch !important;
            align-items: stretch !important;
        }
        .nm_align-content-sm-start {
            -ms-flex-line-pack: start !important;
            align-content: flex-start !important;
        }
        .nm_align-content-sm-end {
            -ms-flex-line-pack: end !important;
            align-content: flex-end !important;
        }
        .nm_align-content-sm-center {
            -ms-flex-line-pack: center !important;
            align-content: center !important;
        }
        .nm_align-content-sm-between {
            -ms-flex-line-pack: justify !important;
            align-content: space-between !important;
        }
        .nm_align-content-sm-around {
            -ms-flex-line-pack: distribute !important;
            align-content: space-around !important;
        }
        .nm_align-content-sm-stretch {
            -ms-flex-line-pack: stretch !important;
            align-content: stretch !important;
        }
        .nm_align-self-sm-auto {
            -ms-flex-item-align: auto !important;
            align-self: auto !important;
        }
        .nm_align-self-sm-start {
            -ms-flex-item-align: start !important;
            align-self: flex-start !important;
        }
        .nm_align-self-sm-end {
            -ms-flex-item-align: end !important;
            align-self: flex-end !important;
        }
        .nm_align-self-sm-center {
            -ms-flex-item-align: center !important;
            align-self: center !important;
        }
        .nm_align-self-sm-baseline {
            -ms-flex-item-align: baseline !important;
            align-self: baseline !important;
        }
        .nm_align-self-sm-stretch {
            -ms-flex-item-align: stretch !important;
            align-self: stretch !important;
        }
    }

    @media (min-width: 768px) {
        .nm_flex-md-row {
            -ms-flex-direction: row !important;
            flex-direction: row !important;
        }
        .nm_flex-md-column {
            -ms-flex-direction: column !important;
            flex-direction: column !important;
        }
        .nm_flex-md-row-reverse {
            -ms-flex-direction: row-reverse !important;
            flex-direction: row-reverse !important;
        }
        .nm_flex-md-column-reverse {
            -ms-flex-direction: column-reverse !important;
            flex-direction: column-reverse !important;
        }
        .nm_flex-md-wrap {
            -ms-flex-wrap: wrap !important;
            flex-wrap: wrap !important;
        }
        .nm_flex-md-nowrap {
            -ms-flex-wrap: nowrap !important;
            flex-wrap: nowrap !important;
        }
        .nm_flex-md-wrap-reverse {
            -ms-flex-wrap: wrap-reverse !important;
            flex-wrap: wrap-reverse !important;
        }
        .nm_flex-md-fill {
            -ms-flex: 1 1 auto !important;
            flex: 1 1 auto !important;
        }
        .nm_flex-md-grow-0 {
            -ms-flex-positive: 0 !important;
            flex-grow: 0 !important;
        }
        .nm_flex-md-grow-1 {
            -ms-flex-positive: 1 !important;
            flex-grow: 1 !important;
        }
        .nm_flex-md-shrink-0 {
            -ms-flex-negative: 0 !important;
            flex-shrink: 0 !important;
        }
        .nm_flex-md-shrink-1 {
            -ms-flex-negative: 1 !important;
            flex-shrink: 1 !important;
        }
        .nm_justify-content-md-start {
            -ms-flex-pack: start !important;
            justify-content: flex-start !important;
        }
        .nm_justify-content-md-end {
            -ms-flex-pack: end !important;
            justify-content: flex-end !important;
        }
        .nm_justify-content-md-center {
            -ms-flex-pack: center !important;
            justify-content: center !important;
        }
        .nm_justify-content-md-between {
            -ms-flex-pack: justify !important;
            justify-content: space-between !important;
        }
        .nm_justify-content-md-around {
            -ms-flex-pack: distribute !important;
            justify-content: space-around !important;
        }
        .nm_align-items-md-start {
            -ms-flex-align: start !important;
            align-items: flex-start !important;
        }
        .nm_align-items-md-end {
            -ms-flex-align: end !important;
            align-items: flex-end !important;
        }
        .nm_align-items-md-center {
            -ms-flex-align: center !important;
            align-items: center !important;
        }
        .nm_align-items-md-baseline {
            -ms-flex-align: baseline !important;
            align-items: baseline !important;
        }
        .nm_align-items-md-stretch {
            -ms-flex-align: stretch !important;
            align-items: stretch !important;
        }
        .nm_align-content-md-start {
            -ms-flex-line-pack: start !important;
            align-content: flex-start !important;
        }
        .nm_align-content-md-end {
            -ms-flex-line-pack: end !important;
            align-content: flex-end !important;
        }
        .nm_align-content-md-center {
            -ms-flex-line-pack: center !important;
            align-content: center !important;
        }
        .nm_align-content-md-between {
            -ms-flex-line-pack: justify !important;
            align-content: space-between !important;
        }
        .nm_align-content-md-around {
            -ms-flex-line-pack: distribute !important;
            align-content: space-around !important;
        }
        .nm_align-content-md-stretch {
            -ms-flex-line-pack: stretch !important;
            align-content: stretch !important;
        }
        .nm_align-self-md-auto {
            -ms-flex-item-align: auto !important;
            align-self: auto !important;
        }
        .nm_align-self-md-start {
            -ms-flex-item-align: start !important;
            align-self: flex-start !important;
        }
        .nm_align-self-md-end {
            -ms-flex-item-align: end !important;
            align-self: flex-end !important;
        }
        .nm_align-self-md-center {
            -ms-flex-item-align: center !important;
            align-self: center !important;
        }
        .nm_align-self-md-baseline {
            -ms-flex-item-align: baseline !important;
            align-self: baseline !important;
        }
        .nm_align-self-md-stretch {
            -ms-flex-item-align: stretch !important;
            align-self: stretch !important;
        }
    }

    @media (min-width: 992px) {
        .nm_flex-lg-row {
            -ms-flex-direction: row !important;
            flex-direction: row !important;
        }
        .nm_flex-lg-column {
            -ms-flex-direction: column !important;
            flex-direction: column !important;
        }
        .nm_flex-lg-row-reverse {
            -ms-flex-direction: row-reverse !important;
            flex-direction: row-reverse !important;
        }
        .nm_flex-lg-column-reverse {
            -ms-flex-direction: column-reverse !important;
            flex-direction: column-reverse !important;
        }
        .nm_flex-lg-wrap {
            -ms-flex-wrap: wrap !important;
            flex-wrap: wrap !important;
        }
        .nm_flex-lg-nowrap {
            -ms-flex-wrap: nowrap !important;
            flex-wrap: nowrap !important;
        }
        .nm_flex-lg-wrap-reverse {
            -ms-flex-wrap: wrap-reverse !important;
            flex-wrap: wrap-reverse !important;
        }
        .nm_flex-lg-fill {
            -ms-flex: 1 1 auto !important;
            flex: 1 1 auto !important;
        }
        .nm_flex-lg-grow-0 {
            -ms-flex-positive: 0 !important;
            flex-grow: 0 !important;
        }
        .nm_flex-lg-grow-1 {
            -ms-flex-positive: 1 !important;
            flex-grow: 1 !important;
        }
        .nm_flex-lg-shrink-0 {
            -ms-flex-negative: 0 !important;
            flex-shrink: 0 !important;
        }
        .nm_flex-lg-shrink-1 {
            -ms-flex-negative: 1 !important;
            flex-shrink: 1 !important;
        }
        .nm_justify-content-lg-start {
            -ms-flex-pack: start !important;
            justify-content: flex-start !important;
        }
        .nm_justify-content-lg-end {
            -ms-flex-pack: end !important;
            justify-content: flex-end !important;
        }
        .nm_justify-content-lg-center {
            -ms-flex-pack: center !important;
            justify-content: center !important;
        }
        .nm_justify-content-lg-between {
            -ms-flex-pack: justify !important;
            justify-content: space-between !important;
        }
        .nm_justify-content-lg-around {
            -ms-flex-pack: distribute !important;
            justify-content: space-around !important;
        }
        .nm_align-items-lg-start {
            -ms-flex-align: start !important;
            align-items: flex-start !important;
        }
        .nm_align-items-lg-end {
            -ms-flex-align: end !important;
            align-items: flex-end !important;
        }
        .nm_align-items-lg-center {
            -ms-flex-align: center !important;
            align-items: center !important;
        }
        .nm_align-items-lg-baseline {
            -ms-flex-align: baseline !important;
            align-items: baseline !important;
        }
        .nm_align-items-lg-stretch {
            -ms-flex-align: stretch !important;
            align-items: stretch !important;
        }
        .nm_align-content-lg-start {
            -ms-flex-line-pack: start !important;
            align-content: flex-start !important;
        }
        .nm_align-content-lg-end {
            -ms-flex-line-pack: end !important;
            align-content: flex-end !important;
        }
        .nm_align-content-lg-center {
            -ms-flex-line-pack: center !important;
            align-content: center !important;
        }
        .nm_align-content-lg-between {
            -ms-flex-line-pack: justify !important;
            align-content: space-between !important;
        }
        .nm_align-content-lg-around {
            -ms-flex-line-pack: distribute !important;
            align-content: space-around !important;
        }
        .nm_align-content-lg-stretch {
            -ms-flex-line-pack: stretch !important;
            align-content: stretch !important;
        }
        .nm_align-self-lg-auto {
            -ms-flex-item-align: auto !important;
            align-self: auto !important;
        }
        .nm_align-self-lg-start {
            -ms-flex-item-align: start !important;
            align-self: flex-start !important;
        }
        .nm_align-self-lg-end {
            -ms-flex-item-align: end !important;
            align-self: flex-end !important;
        }
        .nm_align-self-lg-center {
            -ms-flex-item-align: center !important;
            align-self: center !important;
        }
        .nm_align-self-lg-baseline {
            -ms-flex-item-align: baseline !important;
            align-self: baseline !important;
        }
        .nm_align-self-lg-stretch {
            -ms-flex-item-align: stretch !important;
            align-self: stretch !important;
        }
    }

    @media (min-width: 1200px) {
        .nm_flex-xl-row {
            -ms-flex-direction: row !important;
            flex-direction: row !important;
        }
        .nm_flex-xl-column {
            -ms-flex-direction: column !important;
            flex-direction: column !important;
        }
        .nm_flex-xl-row-reverse {
            -ms-flex-direction: row-reverse !important;
            flex-direction: row-reverse !important;
        }
        .nm_flex-xl-column-reverse {
            -ms-flex-direction: column-reverse !important;
            flex-direction: column-reverse !important;
        }
        .nm_flex-xl-wrap {
            -ms-flex-wrap: wrap !important;
            flex-wrap: wrap !important;
        }
        .nm_flex-xl-nowrap {
            -ms-flex-wrap: nowrap !important;
            flex-wrap: nowrap !important;
        }
        .nm_flex-xl-wrap-reverse {
            -ms-flex-wrap: wrap-reverse !important;
            flex-wrap: wrap-reverse !important;
        }
        .nm_flex-xl-fill {
            -ms-flex: 1 1 auto !important;
            flex: 1 1 auto !important;
        }
        .nm_flex-xl-grow-0 {
            -ms-flex-positive: 0 !important;
            flex-grow: 0 !important;
        }
        .nm_flex-xl-grow-1 {
            -ms-flex-positive: 1 !important;
            flex-grow: 1 !important;
        }
        .nm_flex-xl-shrink-0 {
            -ms-flex-negative: 0 !important;
            flex-shrink: 0 !important;
        }
        .nm_flex-xl-shrink-1 {
            -ms-flex-negative: 1 !important;
            flex-shrink: 1 !important;
        }
        .nm_justify-content-xl-start {
            -ms-flex-pack: start !important;
            justify-content: flex-start !important;
        }
        .nm_justify-content-xl-end {
            -ms-flex-pack: end !important;
            justify-content: flex-end !important;
        }
        .nm_justify-content-xl-center {
            -ms-flex-pack: center !important;
            justify-content: center !important;
        }
        .nm_justify-content-xl-between {
            -ms-flex-pack: justify !important;
            justify-content: space-between !important;
        }
        .nm_justify-content-xl-around {
            -ms-flex-pack: distribute !important;
            justify-content: space-around !important;
        }
        .nm_align-items-xl-start {
            -ms-flex-align: start !important;
            align-items: flex-start !important;
        }
        .nm_align-items-xl-end {
            -ms-flex-align: end !important;
            align-items: flex-end !important;
        }
        .nm_align-items-xl-center {
            -ms-flex-align: center !important;
            align-items: center !important;
        }
        .nm_align-items-xl-baseline {
            -ms-flex-align: baseline !important;
            align-items: baseline !important;
        }
        .nm_align-items-xl-stretch {
            -ms-flex-align: stretch !important;
            align-items: stretch !important;
        }
        .nm_align-content-xl-start {
            -ms-flex-line-pack: start !important;
            align-content: flex-start !important;
        }
        .nm_align-content-xl-end {
            -ms-flex-line-pack: end !important;
            align-content: flex-end !important;
        }
        .nm_align-content-xl-center {
            -ms-flex-line-pack: center !important;
            align-content: center !important;
        }
        .nm_align-content-xl-between {
            -ms-flex-line-pack: justify !important;
            align-content: space-between !important;
        }
        .nm_align-content-xl-around {
            -ms-flex-line-pack: distribute !important;
            align-content: space-around !important;
        }
        .nm_align-content-xl-stretch {
            -ms-flex-line-pack: stretch !important;
            align-content: stretch !important;
        }
        .nm_align-self-xl-auto {
            -ms-flex-item-align: auto !important;
            align-self: auto !important;
        }
        .nm_align-self-xl-start {
            -ms-flex-item-align: start !important;
            align-self: flex-start !important;
        }
        .nm_align-self-xl-end {
            -ms-flex-item-align: end !important;
            align-self: flex-end !important;
        }
        .nm_align-self-xl-center {
            -ms-flex-item-align: center !important;
            align-self: center !important;
        }
        .nm_align-self-xl-baseline {
            -ms-flex-item-align: baseline !important;
            align-self: baseline !important;
        }
        .nm_align-self-xl-stretch {
            -ms-flex-item-align: stretch !important;
            align-self: stretch !important;
        }
    }

    .nm_float-left {
        float: left !important;
    }

    .nm_float-right {
        float: right !important;
    }

    .nm_float-none {
        float: none !important;
    }

    @media (min-width: 576px) {
        .nm_float-sm-left {
            float: left !important;
        }
        .nm_float-sm-right {
            float: right !important;
        }
        .nm_float-sm-none {
            float: none !important;
        }
    }

    @media (min-width: 768px) {
        .nm_float-md-left {
            float: left !important;
        }
        .nm_float-md-right {
            float: right !important;
        }
        .nm_float-md-none {
            float: none !important;
        }
    }

    @media (min-width: 992px) {
        .nm_float-lg-left {
            float: left !important;
        }
        .nm_float-lg-right {
            float: right !important;
        }
        .nm_float-lg-none {
            float: none !important;
        }
    }

    @media (min-width: 1200px) {
        .nm_float-xl-left {
            float: left !important;
        }
        .nm_float-xl-right {
            float: right !important;
        }
        .nm_float-xl-none {
            float: none !important;
        }
    }

    .nm_overflow-auto {
        overflow: auto !important;
    }

    .nm_overflow-hidden {
        overflow: hidden !important;
    }

    .nm_position-static {
        position: static !important;
    }

    .nm_position-relative {
        position: relative !important;
    }

    .nm_position-absolute {
        position: absolute !important;
    }

    .nm_position-fixed {
        position: fixed !important;
    }

    .nm_position-sticky {
        position: -webkit-sticky !important;
        position: sticky !important;
    }

    .nm_fixed-top {
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1030;
    }

    .nm_fixed-bottom {
        position: fixed;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1030;
    }

    @supports ((position: -webkit-sticky) or (position: sticky)) {
        .nm_sticky-top {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 1020;
        }
    }

    .nm_sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .nm_sr-only-focusable:active, .nm_sr-only-focusable:focus {
        position: static;
        width: auto;
        height: auto;
        overflow: visible;
        clip: auto;
        white-space: normal;
    }

    .nm_shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .nm_shadow {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .nm_shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
    }

    .nm_shadow-none {
        box-shadow: none !important;
    }

    .nm_w-25 {
        width: 25% !important;
    }

    .nm_w-50 {
        width: 50% !important;
    }

    .nm_w-75 {
        width: 75% !important;
    }

    .nm_w-100 {
        width: 100% !important;
    }

    .nm_w-auto {
        width: auto !important;
    }

    .nm_h-25 {
        height: 25% !important;
    }

    .nm_h-50 {
        height: 50% !important;
    }

    .nm_h-75 {
        height: 75% !important;
    }

    .nm_h-100 {
        height: 100% !important;
    }

    .nm_h-auto {
        height: auto !important;
    }

    .nm_mw-100 {
        max-width: 100% !important;
    }

    .nm_mh-100 {
        max-height: 100% !important;
    }

    .nm_min-vw-100 {
        min-width: 100vw !important;
    }

    .nm_min-vh-100 {
        min-height: 100vh !important;
    }

    .nm_vw-100 {
        width: 100vw !important;
    }

    .nm_vh-100 {
        height: 100vh !important;
    }

    .nm_stretched-link::after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1;
        pointer-events: auto;
        content: "";
        background-color: rgba(0, 0, 0, 0);
    }

    .nm_m-0 {
        margin: 0 !important;
    }

    .nm_mt-0,
    .nm_my-0 {
        margin-top: 0 !important;
    }

    .nm_mr-0,
    .nm_mx-0 {
        margin-right: 0 !important;
    }

    .nm_mb-0,
    .nm_my-0 {
        margin-bottom: 0 !important;
    }

    .nm_ml-0,
    .nm_mx-0 {
        margin-left: 0 !important;
    }

    .nm_m-1 {
        margin: 0.25rem !important;
    }

    .nm_mt-1,
    .nm_my-1 {
        margin-top: 0.25rem !important;
    }

    .nm_mr-1,
    .nm_mx-1 {
        margin-right: 0.25rem !important;
    }

    .nm_mb-1,
    .nm_my-1 {
        margin-bottom: 0.25rem !important;
    }

    .nm_ml-1,
    .nm_mx-1 {
        margin-left: 0.25rem !important;
    }

    .nm_m-2 {
        margin: 0.5rem !important;
    }

    .nm_mt-2,
    .nm_my-2 {
        margin-top: 0.5rem !important;
    }

    .nm_mr-2,
    .nm_mx-2 {
        margin-right: 0.5rem !important;
    }

    .nm_mb-2,
    .nm_my-2 {
        margin-bottom: 0.5rem !important;
    }

    .nm_ml-2,
    .nm_mx-2 {
        margin-left: 0.5rem !important;
    }

    .nm_m-3 {
        margin: 1rem !important;
    }

    .nm_mt-3,
    .nm_my-3 {
        margin-top: 1rem !important;
    }

    .nm_mr-3,
    .nm_mx-3 {
        margin-right: 1rem !important;
    }

    .nm_mb-3,
    .nm_my-3 {
        margin-bottom: 1rem !important;
    }

    .nm_ml-3,
    .nm_mx-3 {
        margin-left: 1rem !important;
    }

    .nm_m-4 {
        margin: 1.5rem !important;
    }

    .nm_mt-4,
    .nm_my-4 {
        margin-top: 1.5rem !important;
    }

    .nm_mr-4,
    .nm_mx-4 {
        margin-right: 1.5rem !important;
    }

    .nm_mb-4,
    .nm_my-4 {
        margin-bottom: 1.5rem !important;
    }

    .nm_ml-4,
    .nm_mx-4 {
        margin-left: 1.5rem !important;
    }

    .nm_m-5 {
        margin: 3rem !important;
    }

    .nm_mt-5,
    .nm_my-5 {
        margin-top: 3rem !important;
    }

    .nm_mr-5,
    .nm_mx-5 {
        margin-right: 3rem !important;
    }

    .nm_mb-5,
    .nm_my-5 {
        margin-bottom: 3rem !important;
    }

    .nm_ml-5,
    .nm_mx-5 {
        margin-left: 3rem !important;
    }

    .nm_p-0 {
        padding: 0 !important;
    }

    .nm_pt-0,
    .nm_py-0 {
        padding-top: 0 !important;
    }

    .nm_pr-0,
    .nm_px-0 {
        padding-right: 0 !important;
    }

    .nm_pb-0,
    .nm_py-0 {
        padding-bottom: 0 !important;
    }

    .nm_pl-0,
    .nm_px-0 {
        padding-left: 0 !important;
    }

    .nm_p-1 {
        padding: 0.25rem !important;
    }

    .nm_pt-1,
    .nm_py-1 {
        padding-top: 0.25rem !important;
    }

    .nm_pr-1,
    .nm_px-1 {
        padding-right: 0.25rem !important;
    }

    .nm_pb-1,
    .nm_py-1 {
        padding-bottom: 0.25rem !important;
    }

    .nm_pl-1,
    .nm_px-1 {
        padding-left: 0.25rem !important;
    }

    .nm_p-2 {
        padding: 0.5rem !important;
    }

    .nm_pt-2,
    .nm_py-2 {
        padding-top: 0.5rem !important;
    }

    .nm_pr-2,
    .nm_px-2 {
        padding-right: 0.5rem !important;
    }

    .nm_pb-2,
    .nm_py-2 {
        padding-bottom: 0.5rem !important;
    }

    .nm_pl-2,
    .nm_px-2 {
        padding-left: 0.5rem !important;
    }

    .nm_p-3 {
        padding: 1rem !important;
    }

    .nm_pt-3,
    .nm_py-3 {
        padding-top: 1rem !important;
    }

    .nm_pr-3,
    .nm_px-3 {
        padding-right: 1rem !important;
    }

    .nm_pb-3,
    .nm_py-3 {
        padding-bottom: 1rem !important;
    }

    .nm_pl-3,
    .nm_px-3 {
        padding-left: 1rem !important;
    }

    .nm_p-4 {
        padding: 1.5rem !important;
    }

    .nm_pt-4,
    .nm_py-4 {
        padding-top: 1.5rem !important;
    }

    .nm_pr-4,
    .nm_px-4 {
        padding-right: 1.5rem !important;
    }

    .nm_pb-4,
    .nm_py-4 {
        padding-bottom: 1.5rem !important;
    }

    .nm_pl-4,
    .nm_px-4 {
        padding-left: 1.5rem !important;
    }

    .nm_p-5 {
        padding: 3rem !important;
    }

    .nm_pt-5,
    .nm_py-5 {
        padding-top: 3rem !important;
    }

    .nm_pr-5,
    .nm_px-5 {
        padding-right: 3rem !important;
    }

    .nm_pb-5,
    .nm_py-5 {
        padding-bottom: 3rem !important;
    }

    .nm_pl-5,
    .nm_px-5 {
        padding-left: 3rem !important;
    }

    .nm_m-n1 {
        margin: -0.25rem !important;
    }

    .nm_mt-n1,
    .nm_my-n1 {
        margin-top: -0.25rem !important;
    }

    .nm_mr-n1,
    .nm_mx-n1 {
        margin-right: -0.25rem !important;
    }

    .nm_mb-n1,
    .nm_my-n1 {
        margin-bottom: -0.25rem !important;
    }

    .nm_ml-n1,
    .nm_mx-n1 {
        margin-left: -0.25rem !important;
    }

    .nm_m-n2 {
        margin: -0.5rem !important;
    }

    .nm_mt-n2,
    .nm_my-n2 {
        margin-top: -0.5rem !important;
    }

    .nm_mr-n2,
    .nm_mx-n2 {
        margin-right: -0.5rem !important;
    }

    .nm_mb-n2,
    .nm_my-n2 {
        margin-bottom: -0.5rem !important;
    }

    .nm_ml-n2,
    .nm_mx-n2 {
        margin-left: -0.5rem !important;
    }

    .nm_m-n3 {
        margin: -1rem !important;
    }

    .nm_mt-n3,
    .nm_my-n3 {
        margin-top: -1rem !important;
    }

    .nm_mr-n3,
    .nm_mx-n3 {
        margin-right: -1rem !important;
    }

    .nm_mb-n3,
    .nm_my-n3 {
        margin-bottom: -1rem !important;
    }

    .nm_ml-n3,
    .nm_mx-n3 {
        margin-left: -1rem !important;
    }

    .nm_m-n4 {
        margin: -1.5rem !important;
    }

    .nm_mt-n4,
    .nm_my-n4 {
        margin-top: -1.5rem !important;
    }

    .nm_mr-n4,
    .nm_mx-n4 {
        margin-right: -1.5rem !important;
    }

    .nm_mb-n4,
    .nm_my-n4 {
        margin-bottom: -1.5rem !important;
    }

    .nm_ml-n4,
    .nm_mx-n4 {
        margin-left: -1.5rem !important;
    }

    .nm_m-n5 {
        margin: -3rem !important;
    }

    .nm_mt-n5,
    .nm_my-n5 {
        margin-top: -3rem !important;
    }

    .nm_mr-n5,
    .nm_mx-n5 {
        margin-right: -3rem !important;
    }

    .nm_mb-n5,
    .nm_my-n5 {
        margin-bottom: -3rem !important;
    }

    .nm_ml-n5,
    .nm_mx-n5 {
        margin-left: -3rem !important;
    }

    .nm_m-auto {
        margin: auto !important;
    }

    .nm_mt-auto,
    .nm_my-auto {
        margin-top: auto !important;
    }

    .nm_mr-auto,
    .nm_mx-auto {
        margin-right: auto !important;
    }

    .nm_mb-auto,
    .nm_my-auto {
        margin-bottom: auto !important;
    }

    .nm_ml-auto,
    .nm_mx-auto {
        margin-left: auto !important;
    }

    @media (min-width: 576px) {
        .nm_m-sm-0 {
            margin: 0 !important;
        }
        .nm_mt-sm-0,
        .nm_my-sm-0 {
            margin-top: 0 !important;
        }
        .nm_mr-sm-0,
        .nm_mx-sm-0 {
            margin-right: 0 !important;
        }
        .nm_mb-sm-0,
        .nm_my-sm-0 {
            margin-bottom: 0 !important;
        }
        .nm_ml-sm-0,
        .nm_mx-sm-0 {
            margin-left: 0 !important;
        }
        .nm_m-sm-1 {
            margin: 0.25rem !important;
        }
        .nm_mt-sm-1,
        .nm_my-sm-1 {
            margin-top: 0.25rem !important;
        }
        .nm_mr-sm-1,
        .nm_mx-sm-1 {
            margin-right: 0.25rem !important;
        }
        .nm_mb-sm-1,
        .nm_my-sm-1 {
            margin-bottom: 0.25rem !important;
        }
        .nm_ml-sm-1,
        .nm_mx-sm-1 {
            margin-left: 0.25rem !important;
        }
        .nm_m-sm-2 {
            margin: 0.5rem !important;
        }
        .nm_mt-sm-2,
        .nm_my-sm-2 {
            margin-top: 0.5rem !important;
        }
        .nm_mr-sm-2,
        .nm_mx-sm-2 {
            margin-right: 0.5rem !important;
        }
        .nm_mb-sm-2,
        .nm_my-sm-2 {
            margin-bottom: 0.5rem !important;
        }
        .nm_ml-sm-2,
        .nm_mx-sm-2 {
            margin-left: 0.5rem !important;
        }
        .nm_m-sm-3 {
            margin: 1rem !important;
        }
        .nm_mt-sm-3,
        .nm_my-sm-3 {
            margin-top: 1rem !important;
        }
        .nm_mr-sm-3,
        .nm_mx-sm-3 {
            margin-right: 1rem !important;
        }
        .nm_mb-sm-3,
        .nm_my-sm-3 {
            margin-bottom: 1rem !important;
        }
        .nm_ml-sm-3,
        .nm_mx-sm-3 {
            margin-left: 1rem !important;
        }
        .nm_m-sm-4 {
            margin: 1.5rem !important;
        }
        .nm_mt-sm-4,
        .nm_my-sm-4 {
            margin-top: 1.5rem !important;
        }
        .nm_mr-sm-4,
        .nm_mx-sm-4 {
            margin-right: 1.5rem !important;
        }
        .nm_mb-sm-4,
        .nm_my-sm-4 {
            margin-bottom: 1.5rem !important;
        }
        .nm_ml-sm-4,
        .nm_mx-sm-4 {
            margin-left: 1.5rem !important;
        }
        .nm_m-sm-5 {
            margin: 3rem !important;
        }
        .nm_mt-sm-5,
        .nm_my-sm-5 {
            margin-top: 3rem !important;
        }
        .nm_mr-sm-5,
        .nm_mx-sm-5 {
            margin-right: 3rem !important;
        }
        .nm_mb-sm-5,
        .nm_my-sm-5 {
            margin-bottom: 3rem !important;
        }
        .nm_ml-sm-5,
        .nm_mx-sm-5 {
            margin-left: 3rem !important;
        }
        .nm_p-sm-0 {
            padding: 0 !important;
        }
        .nm_pt-sm-0,
        .nm_py-sm-0 {
            padding-top: 0 !important;
        }
        .nm_pr-sm-0,
        .nm_px-sm-0 {
            padding-right: 0 !important;
        }
        .nm_pb-sm-0,
        .nm_py-sm-0 {
            padding-bottom: 0 !important;
        }
        .nm_pl-sm-0,
        .nm_px-sm-0 {
            padding-left: 0 !important;
        }
        .nm_p-sm-1 {
            padding: 0.25rem !important;
        }
        .nm_pt-sm-1,
        .nm_py-sm-1 {
            padding-top: 0.25rem !important;
        }
        .nm_pr-sm-1,
        .nm_px-sm-1 {
            padding-right: 0.25rem !important;
        }
        .nm_pb-sm-1,
        .nm_py-sm-1 {
            padding-bottom: 0.25rem !important;
        }
        .nm_pl-sm-1,
        .nm_px-sm-1 {
            padding-left: 0.25rem !important;
        }
        .nm_p-sm-2 {
            padding: 0.5rem !important;
        }
        .nm_pt-sm-2,
        .nm_py-sm-2 {
            padding-top: 0.5rem !important;
        }
        .nm_pr-sm-2,
        .nm_px-sm-2 {
            padding-right: 0.5rem !important;
        }
        .nm_pb-sm-2,
        .nm_py-sm-2 {
            padding-bottom: 0.5rem !important;
        }
        .nm_pl-sm-2,
        .nm_px-sm-2 {
            padding-left: 0.5rem !important;
        }
        .nm_p-sm-3 {
            padding: 1rem !important;
        }
        .nm_pt-sm-3,
        .nm_py-sm-3 {
            padding-top: 1rem !important;
        }
        .nm_pr-sm-3,
        .nm_px-sm-3 {
            padding-right: 1rem !important;
        }
        .nm_pb-sm-3,
        .nm_py-sm-3 {
            padding-bottom: 1rem !important;
        }
        .nm_pl-sm-3,
        .nm_px-sm-3 {
            padding-left: 1rem !important;
        }
        .nm_p-sm-4 {
            padding: 1.5rem !important;
        }
        .nm_pt-sm-4,
        .nm_py-sm-4 {
            padding-top: 1.5rem !important;
        }
        .nm_pr-sm-4,
        .nm_px-sm-4 {
            padding-right: 1.5rem !important;
        }
        .nm_pb-sm-4,
        .nm_py-sm-4 {
            padding-bottom: 1.5rem !important;
        }
        .nm_pl-sm-4,
        .nm_px-sm-4 {
            padding-left: 1.5rem !important;
        }
        .nm_p-sm-5 {
            padding: 3rem !important;
        }
        .nm_pt-sm-5,
        .nm_py-sm-5 {
            padding-top: 3rem !important;
        }
        .nm_pr-sm-5,
        .nm_px-sm-5 {
            padding-right: 3rem !important;
        }
        .nm_pb-sm-5,
        .nm_py-sm-5 {
            padding-bottom: 3rem !important;
        }
        .nm_pl-sm-5,
        .nm_px-sm-5 {
            padding-left: 3rem !important;
        }
        .nm_m-sm-n1 {
            margin: -0.25rem !important;
        }
        .nm_mt-sm-n1,
        .nm_my-sm-n1 {
            margin-top: -0.25rem !important;
        }
        .nm_mr-sm-n1,
        .nm_mx-sm-n1 {
            margin-right: -0.25rem !important;
        }
        .nm_mb-sm-n1,
        .nm_my-sm-n1 {
            margin-bottom: -0.25rem !important;
        }
        .nm_ml-sm-n1,
        .nm_mx-sm-n1 {
            margin-left: -0.25rem !important;
        }
        .nm_m-sm-n2 {
            margin: -0.5rem !important;
        }
        .nm_mt-sm-n2,
        .nm_my-sm-n2 {
            margin-top: -0.5rem !important;
        }
        .nm_mr-sm-n2,
        .nm_mx-sm-n2 {
            margin-right: -0.5rem !important;
        }
        .nm_mb-sm-n2,
        .nm_my-sm-n2 {
            margin-bottom: -0.5rem !important;
        }
        .nm_ml-sm-n2,
        .nm_mx-sm-n2 {
            margin-left: -0.5rem !important;
        }
        .nm_m-sm-n3 {
            margin: -1rem !important;
        }
        .nm_mt-sm-n3,
        .nm_my-sm-n3 {
            margin-top: -1rem !important;
        }
        .nm_mr-sm-n3,
        .nm_mx-sm-n3 {
            margin-right: -1rem !important;
        }
        .nm_mb-sm-n3,
        .nm_my-sm-n3 {
            margin-bottom: -1rem !important;
        }
        .nm_ml-sm-n3,
        .nm_mx-sm-n3 {
            margin-left: -1rem !important;
        }
        .nm_m-sm-n4 {
            margin: -1.5rem !important;
        }
        .nm_mt-sm-n4,
        .nm_my-sm-n4 {
            margin-top: -1.5rem !important;
        }
        .nm_mr-sm-n4,
        .nm_mx-sm-n4 {
            margin-right: -1.5rem !important;
        }
        .nm_mb-sm-n4,
        .nm_my-sm-n4 {
            margin-bottom: -1.5rem !important;
        }
        .nm_ml-sm-n4,
        .nm_mx-sm-n4 {
            margin-left: -1.5rem !important;
        }
        .nm_m-sm-n5 {
            margin: -3rem !important;
        }
        .nm_mt-sm-n5,
        .nm_my-sm-n5 {
            margin-top: -3rem !important;
        }
        .nm_mr-sm-n5,
        .nm_mx-sm-n5 {
            margin-right: -3rem !important;
        }
        .nm_mb-sm-n5,
        .nm_my-sm-n5 {
            margin-bottom: -3rem !important;
        }
        .nm_ml-sm-n5,
        .nm_mx-sm-n5 {
            margin-left: -3rem !important;
        }
        .nm_m-sm-auto {
            margin: auto !important;
        }
        .nm_mt-sm-auto,
        .nm_my-sm-auto {
            margin-top: auto !important;
        }
        .nm_mr-sm-auto,
        .nm_mx-sm-auto {
            margin-right: auto !important;
        }
        .nm_mb-sm-auto,
        .nm_my-sm-auto {
            margin-bottom: auto !important;
        }
        .nm_ml-sm-auto,
        .nm_mx-sm-auto {
            margin-left: auto !important;
        }
    }

    @media (min-width: 768px) {
        .nm_m-md-0 {
            margin: 0 !important;
        }
        .nm_mt-md-0,
        .nm_my-md-0 {
            margin-top: 0 !important;
        }
        .nm_mr-md-0,
        .nm_mx-md-0 {
            margin-right: 0 !important;
        }
        .nm_mb-md-0,
        .nm_my-md-0 {
            margin-bottom: 0 !important;
        }
        .nm_ml-md-0,
        .nm_mx-md-0 {
            margin-left: 0 !important;
        }
        .nm_m-md-1 {
            margin: 0.25rem !important;
        }
        .nm_mt-md-1,
        .nm_my-md-1 {
            margin-top: 0.25rem !important;
        }
        .nm_mr-md-1,
        .nm_mx-md-1 {
            margin-right: 0.25rem !important;
        }
        .nm_mb-md-1,
        .nm_my-md-1 {
            margin-bottom: 0.25rem !important;
        }
        .nm_ml-md-1,
        .nm_mx-md-1 {
            margin-left: 0.25rem !important;
        }
        .nm_m-md-2 {
            margin: 0.5rem !important;
        }
        .nm_mt-md-2,
        .nm_my-md-2 {
            margin-top: 0.5rem !important;
        }
        .nm_mr-md-2,
        .nm_mx-md-2 {
            margin-right: 0.5rem !important;
        }
        .nm_mb-md-2,
        .nm_my-md-2 {
            margin-bottom: 0.5rem !important;
        }
        .nm_ml-md-2,
        .nm_mx-md-2 {
            margin-left: 0.5rem !important;
        }
        .nm_m-md-3 {
            margin: 1rem !important;
        }
        .nm_mt-md-3,
        .nm_my-md-3 {
            margin-top: 1rem !important;
        }
        .nm_mr-md-3,
        .nm_mx-md-3 {
            margin-right: 1rem !important;
        }
        .nm_mb-md-3,
        .nm_my-md-3 {
            margin-bottom: 1rem !important;
        }
        .nm_ml-md-3,
        .nm_mx-md-3 {
            margin-left: 1rem !important;
        }
        .nm_m-md-4 {
            margin: 1.5rem !important;
        }
        .nm_mt-md-4,
        .nm_my-md-4 {
            margin-top: 1.5rem !important;
        }
        .nm_mr-md-4,
        .nm_mx-md-4 {
            margin-right: 1.5rem !important;
        }
        .nm_mb-md-4,
        .nm_my-md-4 {
            margin-bottom: 1.5rem !important;
        }
        .nm_ml-md-4,
        .nm_mx-md-4 {
            margin-left: 1.5rem !important;
        }
        .nm_m-md-5 {
            margin: 3rem !important;
        }
        .nm_mt-md-5,
        .nm_my-md-5 {
            margin-top: 3rem !important;
        }
        .nm_mr-md-5,
        .nm_mx-md-5 {
            margin-right: 3rem !important;
        }
        .nm_mb-md-5,
        .nm_my-md-5 {
            margin-bottom: 3rem !important;
        }
        .nm_ml-md-5,
        .nm_mx-md-5 {
            margin-left: 3rem !important;
        }
        .nm_p-md-0 {
            padding: 0 !important;
        }
        .nm_pt-md-0,
        .nm_py-md-0 {
            padding-top: 0 !important;
        }
        .nm_pr-md-0,
        .nm_px-md-0 {
            padding-right: 0 !important;
        }
        .nm_pb-md-0,
        .nm_py-md-0 {
            padding-bottom: 0 !important;
        }
        .nm_pl-md-0,
        .nm_px-md-0 {
            padding-left: 0 !important;
        }
        .nm_p-md-1 {
            padding: 0.25rem !important;
        }
        .nm_pt-md-1,
        .nm_py-md-1 {
            padding-top: 0.25rem !important;
        }
        .nm_pr-md-1,
        .nm_px-md-1 {
            padding-right: 0.25rem !important;
        }
        .nm_pb-md-1,
        .nm_py-md-1 {
            padding-bottom: 0.25rem !important;
        }
        .nm_pl-md-1,
        .nm_px-md-1 {
            padding-left: 0.25rem !important;
        }
        .nm_p-md-2 {
            padding: 0.5rem !important;
        }
        .nm_pt-md-2,
        .nm_py-md-2 {
            padding-top: 0.5rem !important;
        }
        .nm_pr-md-2,
        .nm_px-md-2 {
            padding-right: 0.5rem !important;
        }
        .nm_pb-md-2,
        .nm_py-md-2 {
            padding-bottom: 0.5rem !important;
        }
        .nm_pl-md-2,
        .nm_px-md-2 {
            padding-left: 0.5rem !important;
        }
        .nm_p-md-3 {
            padding: 1rem !important;
        }
        .nm_pt-md-3,
        .nm_py-md-3 {
            padding-top: 1rem !important;
        }
        .nm_pr-md-3,
        .nm_px-md-3 {
            padding-right: 1rem !important;
        }
        .nm_pb-md-3,
        .nm_py-md-3 {
            padding-bottom: 1rem !important;
        }
        .nm_pl-md-3,
        .nm_px-md-3 {
            padding-left: 1rem !important;
        }
        .nm_p-md-4 {
            padding: 1.5rem !important;
        }
        .nm_pt-md-4,
        .nm_py-md-4 {
            padding-top: 1.5rem !important;
        }
        .nm_pr-md-4,
        .nm_px-md-4 {
            padding-right: 1.5rem !important;
        }
        .nm_pb-md-4,
        .nm_py-md-4 {
            padding-bottom: 1.5rem !important;
        }
        .nm_pl-md-4,
        .nm_px-md-4 {
            padding-left: 1.5rem !important;
        }
        .nm_p-md-5 {
            padding: 3rem !important;
        }
        .nm_pt-md-5,
        .nm_py-md-5 {
            padding-top: 3rem !important;
        }
        .nm_pr-md-5,
        .nm_px-md-5 {
            padding-right: 3rem !important;
        }
        .nm_pb-md-5,
        .nm_py-md-5 {
            padding-bottom: 3rem !important;
        }
        .nm_pl-md-5,
        .nm_px-md-5 {
            padding-left: 3rem !important;
        }
        .nm_m-md-n1 {
            margin: -0.25rem !important;
        }
        .nm_mt-md-n1,
        .nm_my-md-n1 {
            margin-top: -0.25rem !important;
        }
        .nm_mr-md-n1,
        .nm_mx-md-n1 {
            margin-right: -0.25rem !important;
        }
        .nm_mb-md-n1,
        .nm_my-md-n1 {
            margin-bottom: -0.25rem !important;
        }
        .nm_ml-md-n1,
        .nm_mx-md-n1 {
            margin-left: -0.25rem !important;
        }
        .nm_m-md-n2 {
            margin: -0.5rem !important;
        }
        .nm_mt-md-n2,
        .nm_my-md-n2 {
            margin-top: -0.5rem !important;
        }
        .nm_mr-md-n2,
        .nm_mx-md-n2 {
            margin-right: -0.5rem !important;
        }
        .nm_mb-md-n2,
        .nm_my-md-n2 {
            margin-bottom: -0.5rem !important;
        }
        .nm_ml-md-n2,
        .nm_mx-md-n2 {
            margin-left: -0.5rem !important;
        }
        .nm_m-md-n3 {
            margin: -1rem !important;
        }
        .nm_mt-md-n3,
        .nm_my-md-n3 {
            margin-top: -1rem !important;
        }
        .nm_mr-md-n3,
        .nm_mx-md-n3 {
            margin-right: -1rem !important;
        }
        .nm_mb-md-n3,
        .nm_my-md-n3 {
            margin-bottom: -1rem !important;
        }
        .nm_ml-md-n3,
        .nm_mx-md-n3 {
            margin-left: -1rem !important;
        }
        .nm_m-md-n4 {
            margin: -1.5rem !important;
        }
        .nm_mt-md-n4,
        .nm_my-md-n4 {
            margin-top: -1.5rem !important;
        }
        .nm_mr-md-n4,
        .nm_mx-md-n4 {
            margin-right: -1.5rem !important;
        }
        .nm_mb-md-n4,
        .nm_my-md-n4 {
            margin-bottom: -1.5rem !important;
        }
        .nm_ml-md-n4,
        .nm_mx-md-n4 {
            margin-left: -1.5rem !important;
        }
        .nm_m-md-n5 {
            margin: -3rem !important;
        }
        .nm_mt-md-n5,
        .nm_my-md-n5 {
            margin-top: -3rem !important;
        }
        .nm_mr-md-n5,
        .nm_mx-md-n5 {
            margin-right: -3rem !important;
        }
        .nm_mb-md-n5,
        .nm_my-md-n5 {
            margin-bottom: -3rem !important;
        }
        .nm_ml-md-n5,
        .nm_mx-md-n5 {
            margin-left: -3rem !important;
        }
        .nm_m-md-auto {
            margin: auto !important;
        }
        .nm_mt-md-auto,
        .nm_my-md-auto {
            margin-top: auto !important;
        }
        .nm_mr-md-auto,
        .nm_mx-md-auto {
            margin-right: auto !important;
        }
        .nm_mb-md-auto,
        .nm_my-md-auto {
            margin-bottom: auto !important;
        }
        .nm_ml-md-auto,
        .nm_mx-md-auto {
            margin-left: auto !important;
        }
    }

    @media (min-width: 992px) {
        .nm_m-lg-0 {
            margin: 0 !important;
        }
        .nm_mt-lg-0,
        .nm_my-lg-0 {
            margin-top: 0 !important;
        }
        .nm_mr-lg-0,
        .nm_mx-lg-0 {
            margin-right: 0 !important;
        }
        .nm_mb-lg-0,
        .nm_my-lg-0 {
            margin-bottom: 0 !important;
        }
        .nm_ml-lg-0,
        .nm_mx-lg-0 {
            margin-left: 0 !important;
        }
        .nm_m-lg-1 {
            margin: 0.25rem !important;
        }
        .nm_mt-lg-1,
        .nm_my-lg-1 {
            margin-top: 0.25rem !important;
        }
        .nm_mr-lg-1,
        .nm_mx-lg-1 {
            margin-right: 0.25rem !important;
        }
        .nm_mb-lg-1,
        .nm_my-lg-1 {
            margin-bottom: 0.25rem !important;
        }
        .nm_ml-lg-1,
        .nm_mx-lg-1 {
            margin-left: 0.25rem !important;
        }
        .nm_m-lg-2 {
            margin: 0.5rem !important;
        }
        .nm_mt-lg-2,
        .nm_my-lg-2 {
            margin-top: 0.5rem !important;
        }
        .nm_mr-lg-2,
        .nm_mx-lg-2 {
            margin-right: 0.5rem !important;
        }
        .nm_mb-lg-2,
        .nm_my-lg-2 {
            margin-bottom: 0.5rem !important;
        }
        .nm_ml-lg-2,
        .nm_mx-lg-2 {
            margin-left: 0.5rem !important;
        }
        .nm_m-lg-3 {
            margin: 1rem !important;
        }
        .nm_mt-lg-3,
        .nm_my-lg-3 {
            margin-top: 1rem !important;
        }
        .nm_mr-lg-3,
        .nm_mx-lg-3 {
            margin-right: 1rem !important;
        }
        .nm_mb-lg-3,
        .nm_my-lg-3 {
            margin-bottom: 1rem !important;
        }
        .nm_ml-lg-3,
        .nm_mx-lg-3 {
            margin-left: 1rem !important;
        }
        .nm_m-lg-4 {
            margin: 1.5rem !important;
        }
        .nm_mt-lg-4,
        .nm_my-lg-4 {
            margin-top: 1.5rem !important;
        }
        .nm_mr-lg-4,
        .nm_mx-lg-4 {
            margin-right: 1.5rem !important;
        }
        .nm_mb-lg-4,
        .nm_my-lg-4 {
            margin-bottom: 1.5rem !important;
        }
        .nm_ml-lg-4,
        .nm_mx-lg-4 {
            margin-left: 1.5rem !important;
        }
        .nm_m-lg-5 {
            margin: 3rem !important;
        }
        .nm_mt-lg-5,
        .nm_my-lg-5 {
            margin-top: 3rem !important;
        }
        .nm_mr-lg-5,
        .nm_mx-lg-5 {
            margin-right: 3rem !important;
        }
        .nm_mb-lg-5,
        .nm_my-lg-5 {
            margin-bottom: 3rem !important;
        }
        .nm_ml-lg-5,
        .nm_mx-lg-5 {
            margin-left: 3rem !important;
        }
        .nm_p-lg-0 {
            padding: 0 !important;
        }
        .nm_pt-lg-0,
        .nm_py-lg-0 {
            padding-top: 0 !important;
        }
        .nm_pr-lg-0,
        .nm_px-lg-0 {
            padding-right: 0 !important;
        }
        .nm_pb-lg-0,
        .nm_py-lg-0 {
            padding-bottom: 0 !important;
        }
        .nm_pl-lg-0,
        .nm_px-lg-0 {
            padding-left: 0 !important;
        }
        .nm_p-lg-1 {
            padding: 0.25rem !important;
        }
        .nm_pt-lg-1,
        .nm_py-lg-1 {
            padding-top: 0.25rem !important;
        }
        .nm_pr-lg-1,
        .nm_px-lg-1 {
            padding-right: 0.25rem !important;
        }
        .nm_pb-lg-1,
        .nm_py-lg-1 {
            padding-bottom: 0.25rem !important;
        }
        .nm_pl-lg-1,
        .nm_px-lg-1 {
            padding-left: 0.25rem !important;
        }
        .nm_p-lg-2 {
            padding: 0.5rem !important;
        }
        .nm_pt-lg-2,
        .nm_py-lg-2 {
            padding-top: 0.5rem !important;
        }
        .nm_pr-lg-2,
        .nm_px-lg-2 {
            padding-right: 0.5rem !important;
        }
        .nm_pb-lg-2,
        .nm_py-lg-2 {
            padding-bottom: 0.5rem !important;
        }
        .nm_pl-lg-2,
        .nm_px-lg-2 {
            padding-left: 0.5rem !important;
        }
        .nm_p-lg-3 {
            padding: 1rem !important;
        }
        .nm_pt-lg-3,
        .nm_py-lg-3 {
            padding-top: 1rem !important;
        }
        .nm_pr-lg-3,
        .nm_px-lg-3 {
            padding-right: 1rem !important;
        }
        .nm_pb-lg-3,
        .nm_py-lg-3 {
            padding-bottom: 1rem !important;
        }
        .nm_pl-lg-3,
        .nm_px-lg-3 {
            padding-left: 1rem !important;
        }
        .nm_p-lg-4 {
            padding: 1.5rem !important;
        }
        .nm_pt-lg-4,
        .nm_py-lg-4 {
            padding-top: 1.5rem !important;
        }
        .nm_pr-lg-4,
        .nm_px-lg-4 {
            padding-right: 1.5rem !important;
        }
        .nm_pb-lg-4,
        .nm_py-lg-4 {
            padding-bottom: 1.5rem !important;
        }
        .nm_pl-lg-4,
        .nm_px-lg-4 {
            padding-left: 1.5rem !important;
        }
        .nm_p-lg-5 {
            padding: 3rem !important;
        }
        .nm_pt-lg-5,
        .nm_py-lg-5 {
            padding-top: 3rem !important;
        }
        .nm_pr-lg-5,
        .nm_px-lg-5 {
            padding-right: 3rem !important;
        }
        .nm_pb-lg-5,
        .nm_py-lg-5 {
            padding-bottom: 3rem !important;
        }
        .nm_pl-lg-5,
        .nm_px-lg-5 {
            padding-left: 3rem !important;
        }
        .nm_m-lg-n1 {
            margin: -0.25rem !important;
        }
        .nm_mt-lg-n1,
        .nm_my-lg-n1 {
            margin-top: -0.25rem !important;
        }
        .nm_mr-lg-n1,
        .nm_mx-lg-n1 {
            margin-right: -0.25rem !important;
        }
        .nm_mb-lg-n1,
        .nm_my-lg-n1 {
            margin-bottom: -0.25rem !important;
        }
        .nm_ml-lg-n1,
        .nm_mx-lg-n1 {
            margin-left: -0.25rem !important;
        }
        .nm_m-lg-n2 {
            margin: -0.5rem !important;
        }
        .nm_mt-lg-n2,
        .nm_my-lg-n2 {
            margin-top: -0.5rem !important;
        }
        .nm_mr-lg-n2,
        .nm_mx-lg-n2 {
            margin-right: -0.5rem !important;
        }
        .nm_mb-lg-n2,
        .nm_my-lg-n2 {
            margin-bottom: -0.5rem !important;
        }
        .nm_ml-lg-n2,
        .nm_mx-lg-n2 {
            margin-left: -0.5rem !important;
        }
        .nm_m-lg-n3 {
            margin: -1rem !important;
        }
        .nm_mt-lg-n3,
        .nm_my-lg-n3 {
            margin-top: -1rem !important;
        }
        .nm_mr-lg-n3,
        .nm_mx-lg-n3 {
            margin-right: -1rem !important;
        }
        .nm_mb-lg-n3,
        .nm_my-lg-n3 {
            margin-bottom: -1rem !important;
        }
        .nm_ml-lg-n3,
        .nm_mx-lg-n3 {
            margin-left: -1rem !important;
        }
        .nm_m-lg-n4 {
            margin: -1.5rem !important;
        }
        .nm_mt-lg-n4,
        .nm_my-lg-n4 {
            margin-top: -1.5rem !important;
        }
        .nm_mr-lg-n4,
        .nm_mx-lg-n4 {
            margin-right: -1.5rem !important;
        }
        .nm_mb-lg-n4,
        .nm_my-lg-n4 {
            margin-bottom: -1.5rem !important;
        }
        .nm_ml-lg-n4,
        .nm_mx-lg-n4 {
            margin-left: -1.5rem !important;
        }
        .nm_m-lg-n5 {
            margin: -3rem !important;
        }
        .nm_mt-lg-n5,
        .nm_my-lg-n5 {
            margin-top: -3rem !important;
        }
        .nm_mr-lg-n5,
        .nm_mx-lg-n5 {
            margin-right: -3rem !important;
        }
        .nm_mb-lg-n5,
        .nm_my-lg-n5 {
            margin-bottom: -3rem !important;
        }
        .nm_ml-lg-n5,
        .nm_mx-lg-n5 {
            margin-left: -3rem !important;
        }
        .nm_m-lg-auto {
            margin: auto !important;
        }
        .nm_mt-lg-auto,
        .nm_my-lg-auto {
            margin-top: auto !important;
        }
        .nm_mr-lg-auto,
        .nm_mx-lg-auto {
            margin-right: auto !important;
        }
        .nm_mb-lg-auto,
        .nm_my-lg-auto {
            margin-bottom: auto !important;
        }
        .nm_ml-lg-auto,
        .nm_mx-lg-auto {
            margin-left: auto !important;
        }
    }

    @media (min-width: 1200px) {
        .nm_m-xl-0 {
            margin: 0 !important;
        }
        .nm_mt-xl-0,
        .nm_my-xl-0 {
            margin-top: 0 !important;
        }
        .nm_mr-xl-0,
        .nm_mx-xl-0 {
            margin-right: 0 !important;
        }
        .nm_mb-xl-0,
        .nm_my-xl-0 {
            margin-bottom: 0 !important;
        }
        .nm_ml-xl-0,
        .nm_mx-xl-0 {
            margin-left: 0 !important;
        }
        .nm_m-xl-1 {
            margin: 0.25rem !important;
        }
        .nm_mt-xl-1,
        .nm_my-xl-1 {
            margin-top: 0.25rem !important;
        }
        .nm_mr-xl-1,
        .nm_mx-xl-1 {
            margin-right: 0.25rem !important;
        }
        .nm_mb-xl-1,
        .nm_my-xl-1 {
            margin-bottom: 0.25rem !important;
        }
        .nm_ml-xl-1,
        .nm_mx-xl-1 {
            margin-left: 0.25rem !important;
        }
        .nm_m-xl-2 {
            margin: 0.5rem !important;
        }
        .nm_mt-xl-2,
        .nm_my-xl-2 {
            margin-top: 0.5rem !important;
        }
        .nm_mr-xl-2,
        .nm_mx-xl-2 {
            margin-right: 0.5rem !important;
        }
        .nm_mb-xl-2,
        .nm_my-xl-2 {
            margin-bottom: 0.5rem !important;
        }
        .nm_ml-xl-2,
        .nm_mx-xl-2 {
            margin-left: 0.5rem !important;
        }
        .nm_m-xl-3 {
            margin: 1rem !important;
        }
        .nm_mt-xl-3,
        .nm_my-xl-3 {
            margin-top: 1rem !important;
        }
        .nm_mr-xl-3,
        .nm_mx-xl-3 {
            margin-right: 1rem !important;
        }
        .nm_mb-xl-3,
        .nm_my-xl-3 {
            margin-bottom: 1rem !important;
        }
        .nm_ml-xl-3,
        .nm_mx-xl-3 {
            margin-left: 1rem !important;
        }
        .nm_m-xl-4 {
            margin: 1.5rem !important;
        }
        .nm_mt-xl-4,
        .nm_my-xl-4 {
            margin-top: 1.5rem !important;
        }
        .nm_mr-xl-4,
        .nm_mx-xl-4 {
            margin-right: 1.5rem !important;
        }
        .nm_mb-xl-4,
        .nm_my-xl-4 {
            margin-bottom: 1.5rem !important;
        }
        .nm_ml-xl-4,
        .nm_mx-xl-4 {
            margin-left: 1.5rem !important;
        }
        .nm_m-xl-5 {
            margin: 3rem !important;
        }
        .nm_mt-xl-5,
        .nm_my-xl-5 {
            margin-top: 3rem !important;
        }
        .nm_mr-xl-5,
        .nm_mx-xl-5 {
            margin-right: 3rem !important;
        }
        .nm_mb-xl-5,
        .nm_my-xl-5 {
            margin-bottom: 3rem !important;
        }
        .nm_ml-xl-5,
        .nm_mx-xl-5 {
            margin-left: 3rem !important;
        }
        .nm_p-xl-0 {
            padding: 0 !important;
        }
        .nm_pt-xl-0,
        .nm_py-xl-0 {
            padding-top: 0 !important;
        }
        .nm_pr-xl-0,
        .nm_px-xl-0 {
            padding-right: 0 !important;
        }
        .nm_pb-xl-0,
        .nm_py-xl-0 {
            padding-bottom: 0 !important;
        }
        .nm_pl-xl-0,
        .nm_px-xl-0 {
            padding-left: 0 !important;
        }
        .nm_p-xl-1 {
            padding: 0.25rem !important;
        }
        .nm_pt-xl-1,
        .nm_py-xl-1 {
            padding-top: 0.25rem !important;
        }
        .nm_pr-xl-1,
        .nm_px-xl-1 {
            padding-right: 0.25rem !important;
        }
        .nm_pb-xl-1,
        .nm_py-xl-1 {
            padding-bottom: 0.25rem !important;
        }
        .nm_pl-xl-1,
        .nm_px-xl-1 {
            padding-left: 0.25rem !important;
        }
        .nm_p-xl-2 {
            padding: 0.5rem !important;
        }
        .nm_pt-xl-2,
        .nm_py-xl-2 {
            padding-top: 0.5rem !important;
        }
        .nm_pr-xl-2,
        .nm_px-xl-2 {
            padding-right: 0.5rem !important;
        }
        .nm_pb-xl-2,
        .nm_py-xl-2 {
            padding-bottom: 0.5rem !important;
        }
        .nm_pl-xl-2,
        .nm_px-xl-2 {
            padding-left: 0.5rem !important;
        }
        .nm_p-xl-3 {
            padding: 1rem !important;
        }
        .nm_pt-xl-3,
        .nm_py-xl-3 {
            padding-top: 1rem !important;
        }
        .nm_pr-xl-3,
        .nm_px-xl-3 {
            padding-right: 1rem !important;
        }
        .nm_pb-xl-3,
        .nm_py-xl-3 {
            padding-bottom: 1rem !important;
        }
        .nm_pl-xl-3,
        .nm_px-xl-3 {
            padding-left: 1rem !important;
        }
        .nm_p-xl-4 {
            padding: 1.5rem !important;
        }
        .nm_pt-xl-4,
        .nm_py-xl-4 {
            padding-top: 1.5rem !important;
        }
        .nm_pr-xl-4,
        .nm_px-xl-4 {
            padding-right: 1.5rem !important;
        }
        .nm_pb-xl-4,
        .nm_py-xl-4 {
            padding-bottom: 1.5rem !important;
        }
        .nm_pl-xl-4,
        .nm_px-xl-4 {
            padding-left: 1.5rem !important;
        }
        .nm_p-xl-5 {
            padding: 3rem !important;
        }
        .nm_pt-xl-5,
        .nm_py-xl-5 {
            padding-top: 3rem !important;
        }
        .nm_pr-xl-5,
        .nm_px-xl-5 {
            padding-right: 3rem !important;
        }
        .nm_pb-xl-5,
        .nm_py-xl-5 {
            padding-bottom: 3rem !important;
        }
        .nm_pl-xl-5,
        .nm_px-xl-5 {
            padding-left: 3rem !important;
        }
        .nm_m-xl-n1 {
            margin: -0.25rem !important;
        }
        .nm_mt-xl-n1,
        .nm_my-xl-n1 {
            margin-top: -0.25rem !important;
        }
        .nm_mr-xl-n1,
        .nm_mx-xl-n1 {
            margin-right: -0.25rem !important;
        }
        .nm_mb-xl-n1,
        .nm_my-xl-n1 {
            margin-bottom: -0.25rem !important;
        }
        .nm_ml-xl-n1,
        .nm_mx-xl-n1 {
            margin-left: -0.25rem !important;
        }
        .nm_m-xl-n2 {
            margin: -0.5rem !important;
        }
        .nm_mt-xl-n2,
        .nm_my-xl-n2 {
            margin-top: -0.5rem !important;
        }
        .nm_mr-xl-n2,
        .nm_mx-xl-n2 {
            margin-right: -0.5rem !important;
        }
        .nm_mb-xl-n2,
        .nm_my-xl-n2 {
            margin-bottom: -0.5rem !important;
        }
        .nm_ml-xl-n2,
        .nm_mx-xl-n2 {
            margin-left: -0.5rem !important;
        }
        .nm_m-xl-n3 {
            margin: -1rem !important;
        }
        .nm_mt-xl-n3,
        .nm_my-xl-n3 {
            margin-top: -1rem !important;
        }
        .nm_mr-xl-n3,
        .nm_mx-xl-n3 {
            margin-right: -1rem !important;
        }
        .nm_mb-xl-n3,
        .nm_my-xl-n3 {
            margin-bottom: -1rem !important;
        }
        .nm_ml-xl-n3,
        .nm_mx-xl-n3 {
            margin-left: -1rem !important;
        }
        .nm_m-xl-n4 {
            margin: -1.5rem !important;
        }
        .nm_mt-xl-n4,
        .nm_my-xl-n4 {
            margin-top: -1.5rem !important;
        }
        .nm_mr-xl-n4,
        .nm_mx-xl-n4 {
            margin-right: -1.5rem !important;
        }
        .nm_mb-xl-n4,
        .nm_my-xl-n4 {
            margin-bottom: -1.5rem !important;
        }
        .nm_ml-xl-n4,
        .nm_mx-xl-n4 {
            margin-left: -1.5rem !important;
        }
        .nm_m-xl-n5 {
            margin: -3rem !important;
        }
        .nm_mt-xl-n5,
        .nm_my-xl-n5 {
            margin-top: -3rem !important;
        }
        .nm_mr-xl-n5,
        .nm_mx-xl-n5 {
            margin-right: -3rem !important;
        }
        .nm_mb-xl-n5,
        .nm_my-xl-n5 {
            margin-bottom: -3rem !important;
        }
        .nm_ml-xl-n5,
        .nm_mx-xl-n5 {
            margin-left: -3rem !important;
        }
        .nm_m-xl-auto {
            margin: auto !important;
        }
        .nm_mt-xl-auto,
        .nm_my-xl-auto {
            margin-top: auto !important;
        }
        .nm_mr-xl-auto,
        .nm_mx-xl-auto {
            margin-right: auto !important;
        }
        .nm_mb-xl-auto,
        .nm_my-xl-auto {
            margin-bottom: auto !important;
        }
        .nm_ml-xl-auto,
        .nm_mx-xl-auto {
            margin-left: auto !important;
        }
    }

    .nm_text-monospace {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
    }

    .nm_text-justify {
        text-align: justify !important;
    }

    .nm_text-wrap {
        white-space: normal !important;
    }

    .nm_text-nowrap {
        white-space: nowrap !important;
    }

    .nm_text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .nm_text-left {
        text-align: left !important;
    }

    .nm_text-right {
        text-align: right !important;
    }

    .nm_text-center {
        text-align: center !important;
    }

    @media (min-width: 576px) {
        .nm_text-sm-left {
            text-align: left !important;
        }
        .nm_text-sm-right {
            text-align: right !important;
        }
        .nm_text-sm-center {
            text-align: center !important;
        }
    }

    @media (min-width: 768px) {
        .nm_text-md-left {
            text-align: left !important;
        }
        .nm_text-md-right {
            text-align: right !important;
        }
        .nm_text-md-center {
            text-align: center !important;
        }
    }

    @media (min-width: 992px) {
        .nm_text-lg-left {
            text-align: left !important;
        }
        .nm_text-lg-right {
            text-align: right !important;
        }
        .nm_text-lg-center {
            text-align: center !important;
        }
    }

    @media (min-width: 1200px) {
        .nm_text-xl-left {
            text-align: left !important;
        }
        .nm_text-xl-right {
            text-align: right !important;
        }
        .nm_text-xl-center {
            text-align: center !important;
        }
    }

    .nm_text-lowercase {
        text-transform: lowercase !important;
    }

    .nm_text-uppercase {
        text-transform: uppercase !important;
    }

    .nm_text-capitalize {
        text-transform: capitalize !important;
    }

    .nm_font-weight-light {
        font-weight: 300 !important;
    }

    .nm_font-weight-lighter {
        font-weight: lighter !important;
    }

    .nm_font-weight-normal {
        font-weight: 400 !important;
    }

    .nm_font-weight-bold {
        font-weight: 700 !important;
    }

    .nm_font-weight-bolder {
        font-weight: bolder !important;
    }

    .nm_font-italic {
        font-style: italic !important;
    }

    .nm_text-white {
        color: #fff !important;
    }

    .nm_text-primary {
        color: #007bff !important;
    }

    a.nm_text-primary:hover, a.nm_text-primary:focus {
        color: #0056b3 !important;
    }

    .nm_text-secondary {
        color: #6c757d !important;
    }

    a.nm_text-secondary:hover, a.nm_text-secondary:focus {
        color: #494f54 !important;
    }

    .nm_text-success {
        color: #28a745 !important;
    }

    a.nm_text-success:hover, a.nm_text-success:focus {
        color: #19692c !important;
    }

    .nm_text-info {
        color: #17a2b8 !important;
    }

    a.nm_text-info:hover, a.nm_text-info:focus {
        color: #0f6674 !important;
    }

    .nm_text-warning {
        color: #ffc107 !important;
    }

    a.nm_text-warning:hover, a.nm_text-warning:focus {
        color: #ba8b00 !important;
    }

    .nm_text-danger {
        color: #dc3545 !important;
    }

    a.nm_text-danger:hover, a.nm_text-danger:focus {
        color: #a71d2a !important;
    }

    .nm_text-light {
        color: #f8f9fa !important;
    }

    a.nm_text-light:hover, a.nm_text-light:focus {
        color: #cbd3da !important;
    }

    .nm_text-dark {
        color: #343a40 !important;
    }

    a.nm_text-dark:hover, a.nm_text-dark:focus {
        color: #121416 !important;
    }

    .nm_text-body {
        color: #212529 !important;
    }

    .nm_text-muted {
        color: #6c757d !important;
    }

    .nm_text-black-50 {
        color: rgba(0, 0, 0, 0.5) !important;
    }

    .nm_text-white-50 {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    .nm_text-hide {
        font: 0/0 a;
        color: transparent;
        text-shadow: none;
        background-color: transparent;
        border: 0;
    }

    .nm_text-decoration-none {
        text-decoration: none !important;
    }

    .nm_text-break {
        word-break: break-word !important;
        overflow-wrap: break-word !important;
    }

    .nm_text-reset {
        color: inherit !important;
    }

    .nm_visible {
        visibility: visible !important;
    }

    .nm_invisible {
        visibility: hidden !important;
    }

    @media print {
        *,
        *::before,
        *::after {
            text-shadow: none !important;
            box-shadow: none !important;
        }
        a:not(.nm_btn) {
            text-decoration: underline;
        }
        abbr[title]::after {
            content: " (" attr(title) ")";
        }
        pre {
            white-space: pre-wrap !important;
        }
        pre,
        blockquote {
            border: 1px solid #adb5bd;
            page-break-inside: avoid;
        }
        thead {
            display: table-header-group;
        }
        tr,
        img {
            page-break-inside: avoid;
        }
        p,
        h2,
        h3 {
            orphans: 3;
            widows: 3;
        }
        h2,
        h3 {
            page-break-after: avoid;
        }
        @page {
            size: a3;
        }
        .nm_container {
            min-width: 992px !important;
        }
        .nm_navbar {
            display: none;
        }
        .nm_badge {
            border: 1px solid #000;
        }
        .nm_table {
            border-collapse: collapse !important;
        }
        .nm_table td,
        .nm_table th {
            background-color: #fff !important;
        }
        .nm_table-bordered th,
        .nm_table-bordered td {
            border: 1px solid #dee2e6 !important;
        }
        .nm_table-dark {
            color: inherit;
        }
        .nm_table-dark th,
        .nm_table-dark td,
        .nm_table-dark thead th,
        .nm_table-dark tbody + tbody {
            border-color: #dee2e6;
        }
        .nm_table .nm_thead-dark th {
            color: inherit;
            border-color: #dee2e6;
        }
    }

    /* End bootstrap */
    .nm_no-radius{
        border-radius: 0px !important;
    }

    .nm_no-cursor{
        cursor: default !important;
    }

    .nm_bleu, .nm_bleu:hover{
        background: #00a4c1;
    }

    .nm_nm_bleu-fonce, .nm_nm_bleu-fonce:hover{
        background: #203954;
        color: white;
    }

    .nm_vert, .nm_vert:hover{
        background: #599954;
        color: #333;
    }

    .nm_rouge, .nm_rouge:hover{
        background: #FF5A40;
    }

    .nm_noir, .nm_noir:hover{
        background: #333;
        color: white;
    }

    #nm_toolbar{
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        text-align: left;
        background-color: #fff;
    }

    .nm_container{
        box-sizing: border-box;
    }

</style>
<div id="nm_toolbar">
    <div class="nm_container-fluid nm_fixed-bottom" style="background: #333;">
        <button class="nm_btn nm_noir nm_no-radius nm_no-cursor">Dev</button>
        <div class="nm_btn-group nm_dropup">
            <button type="button" class="nm_btn nm_rouge nm_no-radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo (string) $vController.' - '.(string) $action.' - '.$method; ?>
            </button>
        </div>
        <?php if ($tabOpcache !== null && $tabOpcache['directives']['opcache.enable']) {
            ?>
            <div class="nm_btn-group nm_dropup">
                <button type="button" class="nm_btn nm_noir nm_dropdown-toggle nm_no-radius" data-toggle="nm_dropdown" aria-haspopup="true" aria-expanded="false">
                    OpCache <?php echo 'enable'; ?>
                </button>
                <div class="nm_dropdown-menu">
                    <button class="nm_btn nm_dropdown-item nm_vert" style="cursor: pointer;" data-toggle="nm_modal" data-target="#cache">Dashboard</button>
                    <form method="post" action="<?php echo WEBROOT.$_GET['p']; ?>">
                        <input name="resetCache" type="hidden">
                        <input type="submit" value="Vider le cache" class="nm_btn nm_dropdown-item nm_rouge">
                    </form>
                </div>
            </div>
            <?php
        } else {
            ?>
            <button type="button" class="nm_btn nm_nm_bleu-fonce nm_no-radius" >OpCache disable</button>

            <?php
        }?>
        <?php if (@ini_get_all('xdebug')['xdebug.remote_enable']['local_value'] === '1') {
            ?>
            <!--  --><div class="nm_btn-group nm_dropup">
                <button type="button" class="nm_btn nm_vert nm_dropdown-toggle nm_no-radius" data-toggle="nm_dropdown" aria-haspopup="true" aria-expanded="false">
                    xDebug enable
                </button>
                <div class="nm_dropdown-menu">
                    <button class="nm_btn nm_dropdown-item nm_noir" style="color: white; cursor: pointer;" data-toggle="nm_modal" data-target="#xdebug">Config</button>
                    <?php if (ini_get_all('xdebug')['xdebug.profiler_enable']['local_value'] === '1') {
                        ?>
                        <button class="nm_btn nm_dropdown-item nm_vert nm_no-cursor nm_no-radius">Profiler</button>
                        <?php
                    } else {
                        ?>
                        <button class="nm_btn nm_dropdown-item nm_rouge nm_no-cursor nm_no-radius">Profiler</button>
                        <?php
                    } ?>
                    <button class="nm_btn nm_dropdown-item nm_vert nm_no-cursor nm_no-radius">Debug</button>
                </div>
            </div>
            <?php
        } else {
            ?>
            <button class="nm_btn nm_nm_bleu-fonce nm_no-cursor">xDebug disable</button>
            <?php
        }?>

        <button class="nm_btn nm_rouge nm_no-radius">Time: <?php echo $time->getXdebug() ?> sec</button>
        <div class="nm_btn-group nm_dropup">
            <button type="button" class="nm_btn nm_rouge nm_dropdown-toggle nm_no-radius" data-toggle="nm_dropdown" aria-haspopup="true" aria-expanded="false">
                Caches
            </button>
            <div class="nm_dropdown-menu">
                <form method="post" action="<?php echo WEBROOT . $_GET['p']; ?>" >
                    <input name="resetCacheRoute" type="hidden">
                    <input type="submit" value="Vider: Routing" class="nm_btn nm_dropdown-item nm_bleu" style="width: 100%;">
                </form>
                <form method="post" action="<?php echo WEBROOT . $_GET['p']; ?>" >
                    <input name="resetCacheMon" type="hidden">
                    <input type="submit" value="Vider: Monitoring Data" class="nm_btn nm_dropdown-item nm_bleu" style="width: 100%;">
                </form>
            </div>
        </div>
        <div class="nm_btn-group nm_dropup">
            <button type="button" class="nm_btn nm_bleu nm_dropdown-toggle nm_no-radius" data-toggle="nm_dropdown" aria-haspopup="true" aria-expanded="false">
                PHPUnit
            </button>
            <div class="nm_dropdown-menu">

                <?php
                if($fileIndex === null){
                    echo '<button class="nm_btn nm_dropdown-item nm_vert nm_no-cursor">No report found</button>';
                }else{
                    echo '<button class="nm_btn nm_dropdown-item nm_vert" style="cursor: pointer;" data-toggle="nm_modal" data-target="#report">FullTest</button>';
                }
                ?>
            </div>
        </div>
        <div style="float: right">
            <div class="nm_btn-group nm_dropup">
                <button type="button" class="nm_btn nm_vert nm_dropdown-toggle nm_no-radius" data-toggle="nm_dropdown" aria-haspopup="true" aria-expanded="false">
                    Mods & Lib
                </button>
                <div class="nm_dropdown-menu">
                    <?php
                    try{
                        if (@ini_get_all('xdebug')['xdebug.remote_enable']['local_value'] === '1') {
                            ?>
                            <button class="nm_btn nm_dropdown-item nm_vert nm_no-cursor">xDebug</button>
                            <?php
                        } else {
                            ?>
                            <button class="nm_btn nm_dropdown-item nm_rouge nm_no-cursor">xDebug</button>
                            <?php
                        }
                        if ($tabOpcache['directives']['opcache.enable']) {
                            ?>
                            <button class="nm_btn nm_dropdown-item nm_vert nm_no-cursor">OpCache</button>
                            <?php
                        } else {
                            ?>
                            <button class="nm_btn nm_dropdown-item nm_rouge nm_no-cursor">OpCache</button>
                            <?php
                        }
                    }catch(Throwable $e){}
                    ?>
                    <button class="nm_btn nm_dropdown-item nm_bleu nm_no-cursor">Twig 3.0</button>
                    <button class="nm_btn nm_dropdown-item nm_bleu nm_no-cursor">PHPUnit.8.5</button>
                    <button class="nm_btn nm_dropdown-item nm_bleu nm_no-cursor">PHP-DI 6</button>
                    <button class="nm_btn nm_dropdown-item nm_bleu nm_no-cursor">PHP-REF</button>
                </div>
            </div>
            <button class="nm_btn nm_rouge" data-toggle="nm_modal" data-target="#phpinfo">phpinfo</button>
            <button class="nm_btn nm_bleu nm_no-radius">NoMess.<?php echo $version ?></button>
        </div>
    </div>

    <!-- Button trigger nm_modal -->
    <?php
    try{
        if (@$tabOpcache['directives']['opcache.enable']) {
            ?>
            <div class="nm_modal fade" id="cache" tabindex="-1" role="dialog" aria-labelledby="examplenm_modalLabel" aria-hidden="true">
                <div class="nm_modal-dialog nm_modal-lg" role="document">
                    <div class="nm_modal-content nm_no-radius">
                        <div class="nm_modal-header nm_rouge nm_no-radius">
                            <h5 class="nm_modal-title" id="examplenm_modalLabel">OpCache</h5>
                            <button type="button" class="close" data-dismiss="nm_modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="nm_modal-body">
                            <div class="nm_container">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nm_a nav-link active" style="color: #333;" id="home-tab" data-toggle="tab" href="#stat" role="tab" aria-controls="home" aria-selected="true">Statistiques</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nm_a nav-link" style="color: #333;" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Cache</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nm_a nav-link" style="color: #333;" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Configuration</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" style="color: #333;" id="stat" role="tabpanel" aria-labelledby="home-tab">
                                        <table class="nm_table nm_table-dark">
                                            <thead>
                                            <tr>
                                                <th class="text-center" colspan="2" style="border-right: none"></th>
                                                <th style="border-left: none">Configurations</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th>Scripts en cache</th>
                                                <td><?php echo $tabStatus['opcache_statistics']['num_cached_scripts']; ?> - (<?php echo number_format(((100 * $tabStatus['opcache_statistics']['num_cached_scripts']) / $tabOpcache['directives']['opcache.max_accelerated_files']), 2, ',', ''); ?>%)</td>
                                                <td><?php echo $tabOpcache['directives']['opcache.max_accelerated_files']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Clef en cache</th>
                                                <td><?php echo $tabStatus['opcache_statistics']['num_cached_keys']; ?> - (<?php echo number_format(((100 * $tabStatus['opcache_statistics']['num_cached_keys']) / $tabStatus['opcache_statistics']['max_cached_keys']), 2, ',', ''); ?>%)</td>
                                                <td>/<?php echo $tabStatus['opcache_statistics']['max_cached_keys']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Mémoire utilisé</th>
                                                <td><?php echo $tabStatus['memory_usage']['used_memory']; ?> - (<?php echo number_format(((100 * $tabStatus['memory_usage']['used_memory']) / ($tabStatus['memory_usage']['free_memory'] + $tabStatus['memory_usage']['used_memory'])), 2, ',', ''); ?>%)</td>
                                                <td>/<?php echo $tabStatus['memory_usage']['free_memory'] + $tabStatus['memory_usage']['used_memory']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Clef en cache</th>
                                                <td><?php echo $tabStatus['opcache_statistics']['num_cached_keys']; ?></td>
                                                <td>/<?php echo $tabStatus['opcache_statistics']['max_cached_keys']; ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <table class="nm_table nm_table-dark">
                                            <thead>
                                            <tr>
                                                <th>Path System</th>
                                                <th>Invalidate</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($tabStatus['scripts'] as $key => $value) {
                                                echo '
											<tr>
												<th>'.$key.'</th>
												<td>
													<form method="post" action="' . WEBROOT . $_GET['p'] . '">
														<input type="hidden" name="invalide" value="'.$key.'">
														<input type="submit" class="nm_btn nm_btn-sm nm_no-radius nm_rouge" value="Invalider">
													</form>
												</td>
											</tr>
											';
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                        <table class="nm_table nm_table-dark">
                                            <tbody>
                                            <?php
                                            foreach ($tabOpcache['directives'] as $key => $value) {
                                                if ($value === false) {
                                                    echo '
											<tr>
												<th>'.$key.'</th>
												<td>Off</td>
											</tr>
											';
                                                } elseif ($value === true) {
                                                    echo '
											<tr>
												<th>'.$key.'</th>
												<td>On</td>
											</tr>
											';
                                                } else {
                                                    echo '
											<tr>
												<th>'.$key.'</th>
												<td>'.$value.'</td>
											</tr>
											';
                                                }
                                            } ?>

                                            <tr>
                                                <th>blacklist</th>
                                                <td>
                                                    <?php
                                                    foreach ($tabOpcache['blacklist'] as $value) {
                                                        echo $value.'<br>';
                                                    } ?>
                                                </td>
                                            </tr>
                                            <?php

                                            foreach ($tabOpcache['version'] as $key => $value) {
                                                echo '
										<tr>
											<th>'.$key.'</th>
											<td>'.$value.'</td>
										</tr>
										';
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nm_modal-footer">
                            <button type="button" class="nm_btn nm_btn-secondary nm_no-radius" data-dismiss="nm_modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }catch(Throwable $e){}
    if (@ini_get_all('xdebug')['xdebug.remote_enable']['local_value'] === '1') {
        ?>
        <div class="nm_modal fade" id="xdebug" tabindex="-1" role="dialog" aria-labelledby="examplenm_modalLabel" aria-hidden="true">
            <div class="nm_modal-dialog nm_modal-lg" role="nm_document">
                <div class="nm_modal-content nm_no-radius">
                    <div class="nm_modal-header nm_rouge nm_no-radius">
                        <h5 class="h5 nm_h5 nm_modal-title" id="examplenm_modalLabel">xDebug</h5>
                        <button type="button" class="nm_close" data-dismiss="nm_modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="nm_modal-body">
                        <div class="nm_container">
                            <div class="nm_tab-content" id="myTabContent">
                                <div class="nm_tab-pane fade show nm_active" style="color: #333;" id="stat" role="nm_tabpanel" aria-labelledby="home-tab">
                                    <table class="nm_table nm_table-dark">
                                        <thead>
                                        <tr>
                                            <th class="nm_text-center">Directive</th>
                                            <th style="border-left: none">Local</th>
                                            <th style="border-left: none">Master</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach (ini_get_all('xdebug') as $key => $value) {
                                            ?>
                                            <tr>
                                                <th><?php echo $key; ?></th>
                                                <td><?php echo $value['local_value']; ?></td>
                                                <td><?php echo $value['global_value']; ?></td>
                                            </tr>
                                            <?php
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nm_modal-footer">
                        <button type="button" class="nm_btn nm_btn-secondary nm_no-radius" data-dismiss="nm_modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    ?>
    <div class="nm_modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="examplenm_modalLabel" aria-hidden="true">
        <div class="nm_modal-dialog nm_modal-lg" role="nm_document">
            <div class="nm_modal-content nm_no-radius">
                <div class="nm_modal-header nm_rouge nm_no-radius">
                    <h5 class="h5 nm_h5 nm_modal-title" id="examplenm_modalLabel">Coverage</h5>
                    <button type="button" class="nm_close" data-dismiss="nm_modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="nm_modal-body">
                    <div class="nm_container">
                        <iframe src="<?php echo WEBROOT . $fileIndex ?>" style="width: 100%;" height="500"></iframe>
                    </div>
                </div>
                <div class="nm_modal-footer">
                    <button type="button" class="nm_btn nm_btn-secondary nm_no-radius" data-dismiss="nm_modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>


    <div class="nm_modal fade" id="phpinfo" tabindex="-1" role="dialog" aria-labelledby="examplenm_modalLabel" aria-hidden="true">
        <div class="nm_modal-dialog nm_modal-lg" role="nm_document">
            <div class="nm_modal-content nm_no-radius">
                <div class="nm_modal-header nm_rouge nm_no-radius">
                    <h5 class="h5 nm_h5 nm_modal-title" id="examplenm_modalLabel">Php-Info</h5>
                    <button type="button" class="nm_close" data-dismiss="nm_modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="nm_modal-body">
                    <div class="nm_container">
                        <iframe src="<?php echo ROOT . 'vendor/nomess/kernel/Tools/tools/phpinfo.php'?>" style="width: 100%;" height="500"></iframe>
                    </div>
                </div>
                <div class="nm_modal-footer">
                    <button type="button" class="nm_btn nm_btn-secondary nm_no-radius" data-dismiss="nm_modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    !function(e,t){"use strict";"object"==typeof module&&"object"==typeof module.exports?module.exports=e.document?t(e,!0):function(e){if(!e.document)throw new Error("jQuery requires a window with a document");return t(e)}:t(e)}("undefined"!=typeof window?window:this,function(g,e){"use strict";var t=[],v=g.document,r=Object.getPrototypeOf,s=t.slice,y=t.concat,u=t.push,i=t.indexOf,n={},o=n.toString,m=n.hasOwnProperty,a=m.toString,l=a.call(Object),b={},x=function(e){return"function"==typeof e&&"number"!=typeof e.nodeType},w=function(e){return null!=e&&e===e.window},c={type:!0,src:!0,nonce:!0,noModule:!0};function C(e,t,n){var r,i,o=(n=n||v).createElement("script");if(o.text=e,t)for(r in c)(i=t[r]||t.getAttribute&&t.getAttribute(r))&&o.setAttribute(r,i);n.head.appendChild(o).parentNode.removeChild(o)}function T(e){return null==e?e+"":"object"==typeof e||"function"==typeof e?n[o.call(e)]||"object":typeof e}var f="3.4.1 -ajax,-ajax/jsonp,-ajax/load,-ajax/parseXML,-ajax/script,-ajax/var/location,-ajax/var/nonce,-ajax/var/rquery,-ajax/xhr,-manipulation/_evalUrl,-event/ajax,-effects,-effects/Tween,-effects/animatedSelector",E=function(e,t){return new E.fn.init(e,t)},d=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;function p(e){var t=!!e&&"length"in e&&e.length,n=T(e);return!x(e)&&!w(e)&&("array"===n||0===t||"number"==typeof t&&0<t&&t-1 in e)}E.fn=E.prototype={jquery:f,constructor:E,length:0,toArray:function(){return s.call(this)},get:function(e){return null==e?s.call(this):e<0?this[e+this.length]:this[e]},pushStack:function(e){var t=E.merge(this.constructor(),e);return t.prevObject=this,t},each:function(e){return E.each(this,e)},map:function(n){return this.pushStack(E.map(this,function(e,t){return n.call(e,t,e)}))},slice:function(){return this.pushStack(s.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(e){var t=this.length,n=+e+(e<0?t:0);return this.pushStack(0<=n&&n<t?[this[n]]:[])},end:function(){return this.prevObject||this.constructor()},push:u,sort:t.sort,splice:t.splice},E.extend=E.fn.extend=function(){var e,t,n,r,i,o,a=arguments[0]||{},s=1,u=arguments.length,l=!1;for("boolean"==typeof a&&(l=a,a=arguments[s]||{},s++),"object"==typeof a||x(a)||(a={}),s===u&&(a=this,s--);s<u;s++)if(null!=(e=arguments[s]))for(t in e)r=e[t],"__proto__"!==t&&a!==r&&(l&&r&&(E.isPlainObject(r)||(i=Array.isArray(r)))?(n=a[t],o=i&&!Array.isArray(n)?[]:i||E.isPlainObject(n)?n:{},i=!1,a[t]=E.extend(l,o,r)):void 0!==r&&(a[t]=r));return a},E.extend({expando:"jQuery"+(f+Math.random()).replace(/\D/g,""),isReady:!0,error:function(e){throw new Error(e)},noop:function(){},isPlainObject:function(e){var t,n;return!(!e||"[object Object]"!==o.call(e))&&(!(t=r(e))||"function"==typeof(n=m.call(t,"constructor")&&t.constructor)&&a.call(n)===l)},isEmptyObject:function(e){var t;for(t in e)return!1;return!0},globalEval:function(e,t){C(e,{nonce:t&&t.nonce})},each:function(e,t){var n,r=0;if(p(e)){for(n=e.length;r<n;r++)if(!1===t.call(e[r],r,e[r]))break}else for(r in e)if(!1===t.call(e[r],r,e[r]))break;return e},trim:function(e){return null==e?"":(e+"").replace(d,"")},makeArray:function(e,t){var n=t||[];return null!=e&&(p(Object(e))?E.merge(n,"string"==typeof e?[e]:e):u.call(n,e)),n},inArray:function(e,t,n){return null==t?-1:i.call(t,e,n)},merge:function(e,t){for(var n=+t.length,r=0,i=e.length;r<n;r++)e[i++]=t[r];return e.length=i,e},grep:function(e,t,n){for(var r=[],i=0,o=e.length,a=!n;i<o;i++)!t(e[i],i)!==a&&r.push(e[i]);return r},map:function(e,t,n){var r,i,o=0,a=[];if(p(e))for(r=e.length;o<r;o++)null!=(i=t(e[o],o,n))&&a.push(i);else for(o in e)null!=(i=t(e[o],o,n))&&a.push(i);return y.apply([],a)},guid:1,support:b}),"function"==typeof Symbol&&(E.fn[Symbol.iterator]=t[Symbol.iterator]),E.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "),function(e,t){n["[object "+t+"]"]=t.toLowerCase()});var h=function(n){var e,p,x,o,i,h,f,g,w,u,l,C,T,a,E,v,s,c,y,N="sizzle"+1*new Date,m=n.document,A=0,r=0,d=ue(),b=ue(),k=ue(),S=ue(),D=function(e,t){return e===t&&(l=!0),0},L={}.hasOwnProperty,t=[],j=t.pop,q=t.push,O=t.push,P=t.slice,H=function(e,t){for(var n=0,r=e.length;n<r;n++)if(e[n]===t)return n;return-1},I="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",R="[\\x20\\t\\r\\n\\f]",B="(?:\\\\.|[\\w-]|[^\0-\\xa0])+",M="\\["+R+"*("+B+")(?:"+R+"*([*^$|!~]?=)"+R+"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|("+B+"))|)"+R+"*\\]",W=":("+B+")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|"+M+")*)|.*)\\)|)",$=new RegExp(R+"+","g"),F=new RegExp("^"+R+"+|((?:^|[^\\\\])(?:\\\\.)*)"+R+"+$","g"),z=new RegExp("^"+R+"*,"+R+"*"),_=new RegExp("^"+R+"*([>+~]|"+R+")"+R+"*"),U=new RegExp(R+"|>"),V=new RegExp(W),X=new RegExp("^"+B+"$"),Q={ID:new RegExp("^#("+B+")"),CLASS:new RegExp("^\\.("+B+")"),TAG:new RegExp("^("+B+"|[*])"),ATTR:new RegExp("^"+M),PSEUDO:new RegExp("^"+W),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+R+"*(even|odd|(([+-]|)(\\d*)n|)"+R+"*(?:([+-]|)"+R+"*(\\d+)|))"+R+"*\\)|)","i"),bool:new RegExp("^(?:"+I+")$","i"),needsContext:new RegExp("^"+R+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+R+"*((?:-\\d)?\\d*)"+R+"*\\)|)(?=[^-]|$)","i")},Y=/HTML$/i,G=/^(?:input|select|textarea|button)$/i,K=/^h\d$/i,J=/^[^{]+\{\s*\[native \w/,Z=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,ee=/[+~]/,te=new RegExp("\\\\([\\da-f]{1,6}"+R+"?|("+R+")|.)","ig"),ne=function(e,t,n){var r="0x"+t-65536;return r!=r||n?t:r<0?String.fromCharCode(r+65536):String.fromCharCode(r>>10|55296,1023&r|56320)},re=/([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,ie=function(e,t){return t?"\0"===e?"\ufffd":e.slice(0,-1)+"\\"+e.charCodeAt(e.length-1).toString(16)+" ":"\\"+e},oe=function(){C()},ae=xe(function(e){return!0===e.disabled&&"fieldset"===e.nodeName.toLowerCase()},{dir:"parentNode",next:"legend"});try{O.apply(t=P.call(m.childNodes),m.childNodes),t[m.childNodes.length].nodeType}catch(e){O={apply:t.length?function(e,t){q.apply(e,P.call(t))}:function(e,t){var n=e.length,r=0;while(e[n++]=t[r++]);e.length=n-1}}}function se(t,e,n,r){var i,o,a,s,u,l,c,f=e&&e.ownerDocument,d=e?e.nodeType:9;if(n=n||[],"string"!=typeof t||!t||1!==d&&9!==d&&11!==d)return n;if(!r&&((e?e.ownerDocument||e:m)!==T&&C(e),e=e||T,E)){if(11!==d&&(u=Z.exec(t)))if(i=u[1]){if(9===d){if(!(a=e.getElementById(i)))return n;if(a.id===i)return n.push(a),n}else if(f&&(a=f.getElementById(i))&&y(e,a)&&a.id===i)return n.push(a),n}else{if(u[2])return O.apply(n,e.getElementsByTagName(t)),n;if((i=u[3])&&p.getElementsByClassName&&e.getElementsByClassName)return O.apply(n,e.getElementsByClassName(i)),n}if(p.qsa&&!S[t+" "]&&(!v||!v.test(t))&&(1!==d||"object"!==e.nodeName.toLowerCase())){if(c=t,f=e,1===d&&U.test(t)){(s=e.getAttribute("id"))?s=s.replace(re,ie):e.setAttribute("id",s=N),o=(l=h(t)).length;while(o--)l[o]="#"+s+" "+be(l[o]);c=l.join(","),f=ee.test(t)&&ye(e.parentNode)||e}try{return O.apply(n,f.querySelectorAll(c)),n}catch(e){S(t,!0)}finally{s===N&&e.removeAttribute("id")}}}return g(t.replace(F,"$1"),e,n,r)}function ue(){var r=[];return function e(t,n){return r.push(t+" ")>x.cacheLength&&delete e[r.shift()],e[t+" "]=n}}function le(e){return e[N]=!0,e}function ce(e){var t=T.createElement("fieldset");try{return!!e(t)}catch(e){return!1}finally{t.parentNode&&t.parentNode.removeChild(t),t=null}}function fe(e,t){var n=e.split("|"),r=n.length;while(r--)x.attrHandle[n[r]]=t}function de(e,t){var n=t&&e,r=n&&1===e.nodeType&&1===t.nodeType&&e.sourceIndex-t.sourceIndex;if(r)return r;if(n)while(n=n.nextSibling)if(n===t)return-1;return e?1:-1}function pe(t){return function(e){return"input"===e.nodeName.toLowerCase()&&e.type===t}}function he(n){return function(e){var t=e.nodeName.toLowerCase();return("input"===t||"button"===t)&&e.type===n}}function ge(t){return function(e){return"form"in e?e.parentNode&&!1===e.disabled?"label"in e?"label"in e.parentNode?e.parentNode.disabled===t:e.disabled===t:e.isDisabled===t||e.isDisabled!==!t&&ae(e)===t:e.disabled===t:"label"in e&&e.disabled===t}}function ve(a){return le(function(o){return o=+o,le(function(e,t){var n,r=a([],e.length,o),i=r.length;while(i--)e[n=r[i]]&&(e[n]=!(t[n]=e[n]))})})}function ye(e){return e&&"undefined"!=typeof e.getElementsByTagName&&e}for(e in p=se.support={},i=se.isXML=function(e){var t=e.namespaceURI,n=(e.ownerDocument||e).documentElement;return!Y.test(t||n&&n.nodeName||"HTML")},C=se.setDocument=function(e){var t,n,r=e?e.ownerDocument||e:m;return r!==T&&9===r.nodeType&&r.documentElement&&(a=(T=r).documentElement,E=!i(T),m!==T&&(n=T.defaultView)&&n.top!==n&&(n.addEventListener?n.addEventListener("unload",oe,!1):n.attachEvent&&n.attachEvent("onunload",oe)),p.attributes=ce(function(e){return e.className="i",!e.getAttribute("className")}),p.getElementsByTagName=ce(function(e){return e.appendChild(T.createComment("")),!e.getElementsByTagName("*").length}),p.getElementsByClassName=J.test(T.getElementsByClassName),p.getById=ce(function(e){return a.appendChild(e).id=N,!T.getElementsByName||!T.getElementsByName(N).length}),p.getById?(x.filter.ID=function(e){var t=e.replace(te,ne);return function(e){return e.getAttribute("id")===t}},x.find.ID=function(e,t){if("undefined"!=typeof t.getElementById&&E){var n=t.getElementById(e);return n?[n]:[]}}):(x.filter.ID=function(e){var n=e.replace(te,ne);return function(e){var t="undefined"!=typeof e.getAttributeNode&&e.getAttributeNode("id");return t&&t.value===n}},x.find.ID=function(e,t){if("undefined"!=typeof t.getElementById&&E){var n,r,i,o=t.getElementById(e);if(o){if((n=o.getAttributeNode("id"))&&n.value===e)return[o];i=t.getElementsByName(e),r=0;while(o=i[r++])if((n=o.getAttributeNode("id"))&&n.value===e)return[o]}return[]}}),x.find.TAG=p.getElementsByTagName?function(e,t){return"undefined"!=typeof t.getElementsByTagName?t.getElementsByTagName(e):p.qsa?t.querySelectorAll(e):void 0}:function(e,t){var n,r=[],i=0,o=t.getElementsByTagName(e);if("*"===e){while(n=o[i++])1===n.nodeType&&r.push(n);return r}return o},x.find.CLASS=p.getElementsByClassName&&function(e,t){if("undefined"!=typeof t.getElementsByClassName&&E)return t.getElementsByClassName(e)},s=[],v=[],(p.qsa=J.test(T.querySelectorAll))&&(ce(function(e){a.appendChild(e).innerHTML="<a id='"+N+"'></a><select id='"+N+"-\r\\' msallowcapture=''><option selected=''></option></select>",e.querySelectorAll("[msallowcapture^='']").length&&v.push("[*^$]="+R+"*(?:''|\"\")"),e.querySelectorAll("[selected]").length||v.push("\\["+R+"*(?:value|"+I+")"),e.querySelectorAll("[id~="+N+"-]").length||v.push("~="),e.querySelectorAll(":checked").length||v.push(":checked"),e.querySelectorAll("a#"+N+"+*").length||v.push(".#.+[+~]")}),ce(function(e){e.innerHTML="<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";var t=T.createElement("input");t.setAttribute("type","hidden"),e.appendChild(t).setAttribute("name","D"),e.querySelectorAll("[name=d]").length&&v.push("name"+R+"*[*^$|!~]?="),2!==e.querySelectorAll(":enabled").length&&v.push(":enabled",":disabled"),a.appendChild(e).disabled=!0,2!==e.querySelectorAll(":disabled").length&&v.push(":enabled",":disabled"),e.querySelectorAll("*,:x"),v.push(",.*:")})),(p.matchesSelector=J.test(c=a.matches||a.webkitMatchesSelector||a.mozMatchesSelector||a.oMatchesSelector||a.msMatchesSelector))&&ce(function(e){p.disconnectedMatch=c.call(e,"*"),c.call(e,"[s!='']:x"),s.push("!=",W)}),v=v.length&&new RegExp(v.join("|")),s=s.length&&new RegExp(s.join("|")),t=J.test(a.compareDocumentPosition),y=t||J.test(a.contains)?function(e,t){var n=9===e.nodeType?e.documentElement:e,r=t&&t.parentNode;return e===r||!(!r||1!==r.nodeType||!(n.contains?n.contains(r):e.compareDocumentPosition&&16&e.compareDocumentPosition(r)))}:function(e,t){if(t)while(t=t.parentNode)if(t===e)return!0;return!1},D=t?function(e,t){if(e===t)return l=!0,0;var n=!e.compareDocumentPosition-!t.compareDocumentPosition;return n||(1&(n=(e.ownerDocument||e)===(t.ownerDocument||t)?e.compareDocumentPosition(t):1)||!p.sortDetached&&t.compareDocumentPosition(e)===n?e===T||e.ownerDocument===m&&y(m,e)?-1:t===T||t.ownerDocument===m&&y(m,t)?1:u?H(u,e)-H(u,t):0:4&n?-1:1)}:function(e,t){if(e===t)return l=!0,0;var n,r=0,i=e.parentNode,o=t.parentNode,a=[e],s=[t];if(!i||!o)return e===T?-1:t===T?1:i?-1:o?1:u?H(u,e)-H(u,t):0;if(i===o)return de(e,t);n=e;while(n=n.parentNode)a.unshift(n);n=t;while(n=n.parentNode)s.unshift(n);while(a[r]===s[r])r++;return r?de(a[r],s[r]):a[r]===m?-1:s[r]===m?1:0}),T},se.matches=function(e,t){return se(e,null,null,t)},se.matchesSelector=function(e,t){if((e.ownerDocument||e)!==T&&C(e),p.matchesSelector&&E&&!S[t+" "]&&(!s||!s.test(t))&&(!v||!v.test(t)))try{var n=c.call(e,t);if(n||p.disconnectedMatch||e.document&&11!==e.document.nodeType)return n}catch(e){S(t,!0)}return 0<se(t,T,null,[e]).length},se.contains=function(e,t){return(e.ownerDocument||e)!==T&&C(e),y(e,t)},se.attr=function(e,t){(e.ownerDocument||e)!==T&&C(e);var n=x.attrHandle[t.toLowerCase()],r=n&&L.call(x.attrHandle,t.toLowerCase())?n(e,t,!E):void 0;return void 0!==r?r:p.attributes||!E?e.getAttribute(t):(r=e.getAttributeNode(t))&&r.specified?r.value:null},se.escape=function(e){return(e+"").replace(re,ie)},se.error=function(e){throw new Error("Syntax error, unrecognized expression: "+e)},se.uniqueSort=function(e){var t,n=[],r=0,i=0;if(l=!p.detectDuplicates,u=!p.sortStable&&e.slice(0),e.sort(D),l){while(t=e[i++])t===e[i]&&(r=n.push(i));while(r--)e.splice(n[r],1)}return u=null,e},o=se.getText=function(e){var t,n="",r=0,i=e.nodeType;if(i){if(1===i||9===i||11===i){if("string"==typeof e.textContent)return e.textContent;for(e=e.firstChild;e;e=e.nextSibling)n+=o(e)}else if(3===i||4===i)return e.nodeValue}else while(t=e[r++])n+=o(t);return n},(x=se.selectors={cacheLength:50,createPseudo:le,match:Q,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(e){return e[1]=e[1].replace(te,ne),e[3]=(e[3]||e[4]||e[5]||"").replace(te,ne),"~="===e[2]&&(e[3]=" "+e[3]+" "),e.slice(0,4)},CHILD:function(e){return e[1]=e[1].toLowerCase(),"nth"===e[1].slice(0,3)?(e[3]||se.error(e[0]),e[4]=+(e[4]?e[5]+(e[6]||1):2*("even"===e[3]||"odd"===e[3])),e[5]=+(e[7]+e[8]||"odd"===e[3])):e[3]&&se.error(e[0]),e},PSEUDO:function(e){var t,n=!e[6]&&e[2];return Q.CHILD.test(e[0])?null:(e[3]?e[2]=e[4]||e[5]||"":n&&V.test(n)&&(t=h(n,!0))&&(t=n.indexOf(")",n.length-t)-n.length)&&(e[0]=e[0].slice(0,t),e[2]=n.slice(0,t)),e.slice(0,3))}},filter:{TAG:function(e){var t=e.replace(te,ne).toLowerCase();return"*"===e?function(){return!0}:function(e){return e.nodeName&&e.nodeName.toLowerCase()===t}},CLASS:function(e){var t=d[e+" "];return t||(t=new RegExp("(^|"+R+")"+e+"("+R+"|$)"))&&d(e,function(e){return t.test("string"==typeof e.className&&e.className||"undefined"!=typeof e.getAttribute&&e.getAttribute("class")||"")})},ATTR:function(n,r,i){return function(e){var t=se.attr(e,n);return null==t?"!="===r:!r||(t+="","="===r?t===i:"!="===r?t!==i:"^="===r?i&&0===t.indexOf(i):"*="===r?i&&-1<t.indexOf(i):"$="===r?i&&t.slice(-i.length)===i:"~="===r?-1<(" "+t.replace($," ")+" ").indexOf(i):"|="===r&&(t===i||t.slice(0,i.length+1)===i+"-"))}},CHILD:function(h,e,t,g,v){var y="nth"!==h.slice(0,3),m="last"!==h.slice(-4),b="of-type"===e;return 1===g&&0===v?function(e){return!!e.parentNode}:function(e,t,n){var r,i,o,a,s,u,l=y!==m?"nextSibling":"previousSibling",c=e.parentNode,f=b&&e.nodeName.toLowerCase(),d=!n&&!b,p=!1;if(c){if(y){while(l){a=e;while(a=a[l])if(b?a.nodeName.toLowerCase()===f:1===a.nodeType)return!1;u=l="only"===h&&!u&&"nextSibling"}return!0}if(u=[m?c.firstChild:c.lastChild],m&&d){p=(s=(r=(i=(o=(a=c)[N]||(a[N]={}))[a.uniqueID]||(o[a.uniqueID]={}))[h]||[])[0]===A&&r[1])&&r[2],a=s&&c.childNodes[s];while(a=++s&&a&&a[l]||(p=s=0)||u.pop())if(1===a.nodeType&&++p&&a===e){i[h]=[A,s,p];break}}else if(d&&(p=s=(r=(i=(o=(a=e)[N]||(a[N]={}))[a.uniqueID]||(o[a.uniqueID]={}))[h]||[])[0]===A&&r[1]),!1===p)while(a=++s&&a&&a[l]||(p=s=0)||u.pop())if((b?a.nodeName.toLowerCase()===f:1===a.nodeType)&&++p&&(d&&((i=(o=a[N]||(a[N]={}))[a.uniqueID]||(o[a.uniqueID]={}))[h]=[A,p]),a===e))break;return(p-=v)===g||p%g==0&&0<=p/g}}},PSEUDO:function(e,o){var t,a=x.pseudos[e]||x.setFilters[e.toLowerCase()]||se.error("unsupported pseudo: "+e);return a[N]?a(o):1<a.length?(t=[e,e,"",o],x.setFilters.hasOwnProperty(e.toLowerCase())?le(function(e,t){var n,r=a(e,o),i=r.length;while(i--)e[n=H(e,r[i])]=!(t[n]=r[i])}):function(e){return a(e,0,t)}):a}},pseudos:{not:le(function(e){var r=[],i=[],s=f(e.replace(F,"$1"));return s[N]?le(function(e,t,n,r){var i,o=s(e,null,r,[]),a=e.length;while(a--)(i=o[a])&&(e[a]=!(t[a]=i))}):function(e,t,n){return r[0]=e,s(r,null,n,i),r[0]=null,!i.pop()}}),has:le(function(t){return function(e){return 0<se(t,e).length}}),contains:le(function(t){return t=t.replace(te,ne),function(e){return-1<(e.textContent||o(e)).indexOf(t)}}),lang:le(function(n){return X.test(n||"")||se.error("unsupported lang: "+n),n=n.replace(te,ne).toLowerCase(),function(e){var t;do{if(t=E?e.lang:e.getAttribute("xml:lang")||e.getAttribute("lang"))return(t=t.toLowerCase())===n||0===t.indexOf(n+"-")}while((e=e.parentNode)&&1===e.nodeType);return!1}}),target:function(e){var t=n.location&&n.location.hash;return t&&t.slice(1)===e.id},root:function(e){return e===a},focus:function(e){return e===T.activeElement&&(!T.hasFocus||T.hasFocus())&&!!(e.type||e.href||~e.tabIndex)},enabled:ge(!1),disabled:ge(!0),checked:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&!!e.checked||"option"===t&&!!e.selected},selected:function(e){return e.parentNode&&e.parentNode.selectedIndex,!0===e.selected},empty:function(e){for(e=e.firstChild;e;e=e.nextSibling)if(e.nodeType<6)return!1;return!0},parent:function(e){return!x.pseudos.empty(e)},header:function(e){return K.test(e.nodeName)},input:function(e){return G.test(e.nodeName)},button:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&"button"===e.type||"button"===t},text:function(e){var t;return"input"===e.nodeName.toLowerCase()&&"text"===e.type&&(null==(t=e.getAttribute("type"))||"text"===t.toLowerCase())},first:ve(function(){return[0]}),last:ve(function(e,t){return[t-1]}),eq:ve(function(e,t,n){return[n<0?n+t:n]}),even:ve(function(e,t){for(var n=0;n<t;n+=2)e.push(n);return e}),odd:ve(function(e,t){for(var n=1;n<t;n+=2)e.push(n);return e}),lt:ve(function(e,t,n){for(var r=n<0?n+t:t<n?t:n;0<=--r;)e.push(r);return e}),gt:ve(function(e,t,n){for(var r=n<0?n+t:n;++r<t;)e.push(r);return e})}}).pseudos.nth=x.pseudos.eq,{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})x.pseudos[e]=pe(e);for(e in{submit:!0,reset:!0})x.pseudos[e]=he(e);function me(){}function be(e){for(var t=0,n=e.length,r="";t<n;t++)r+=e[t].value;return r}function xe(s,e,t){var u=e.dir,l=e.next,c=l||u,f=t&&"parentNode"===c,d=r++;return e.first?function(e,t,n){while(e=e[u])if(1===e.nodeType||f)return s(e,t,n);return!1}:function(e,t,n){var r,i,o,a=[A,d];if(n){while(e=e[u])if((1===e.nodeType||f)&&s(e,t,n))return!0}else while(e=e[u])if(1===e.nodeType||f)if(i=(o=e[N]||(e[N]={}))[e.uniqueID]||(o[e.uniqueID]={}),l&&l===e.nodeName.toLowerCase())e=e[u]||e;else{if((r=i[c])&&r[0]===A&&r[1]===d)return a[2]=r[2];if((i[c]=a)[2]=s(e,t,n))return!0}return!1}}function we(i){return 1<i.length?function(e,t,n){var r=i.length;while(r--)if(!i[r](e,t,n))return!1;return!0}:i[0]}function Ce(e,t,n,r,i){for(var o,a=[],s=0,u=e.length,l=null!=t;s<u;s++)(o=e[s])&&(n&&!n(o,r,i)||(a.push(o),l&&t.push(s)));return a}function Te(p,h,g,v,y,e){return v&&!v[N]&&(v=Te(v)),y&&!y[N]&&(y=Te(y,e)),le(function(e,t,n,r){var i,o,a,s=[],u=[],l=t.length,c=e||function(e,t,n){for(var r=0,i=t.length;r<i;r++)se(e,t[r],n);return n}(h||"*",n.nodeType?[n]:n,[]),f=!p||!e&&h?c:Ce(c,s,p,n,r),d=g?y||(e?p:l||v)?[]:t:f;if(g&&g(f,d,n,r),v){i=Ce(d,u),v(i,[],n,r),o=i.length;while(o--)(a=i[o])&&(d[u[o]]=!(f[u[o]]=a))}if(e){if(y||p){if(y){i=[],o=d.length;while(o--)(a=d[o])&&i.push(f[o]=a);y(null,d=[],i,r)}o=d.length;while(o--)(a=d[o])&&-1<(i=y?H(e,a):s[o])&&(e[i]=!(t[i]=a))}}else d=Ce(d===t?d.splice(l,d.length):d),y?y(null,t,d,r):O.apply(t,d)})}function Ee(e){for(var i,t,n,r=e.length,o=x.relative[e[0].type],a=o||x.relative[" "],s=o?1:0,u=xe(function(e){return e===i},a,!0),l=xe(function(e){return-1<H(i,e)},a,!0),c=[function(e,t,n){var r=!o&&(n||t!==w)||((i=t).nodeType?u(e,t,n):l(e,t,n));return i=null,r}];s<r;s++)if(t=x.relative[e[s].type])c=[xe(we(c),t)];else{if((t=x.filter[e[s].type].apply(null,e[s].matches))[N]){for(n=++s;n<r;n++)if(x.relative[e[n].type])break;return Te(1<s&&we(c),1<s&&be(e.slice(0,s-1).concat({value:" "===e[s-2].type?"*":""})).replace(F,"$1"),t,s<n&&Ee(e.slice(s,n)),n<r&&Ee(e=e.slice(n)),n<r&&be(e))}c.push(t)}return we(c)}return me.prototype=x.filters=x.pseudos,x.setFilters=new me,h=se.tokenize=function(e,t){var n,r,i,o,a,s,u,l=b[e+" "];if(l)return t?0:l.slice(0);a=e,s=[],u=x.preFilter;while(a){for(o in n&&!(r=z.exec(a))||(r&&(a=a.slice(r[0].length)||a),s.push(i=[])),n=!1,(r=_.exec(a))&&(n=r.shift(),i.push({value:n,type:r[0].replace(F," ")}),a=a.slice(n.length)),x.filter)!(r=Q[o].exec(a))||u[o]&&!(r=u[o](r))||(n=r.shift(),i.push({value:n,type:o,matches:r}),a=a.slice(n.length));if(!n)break}return t?a.length:a?se.error(e):b(e,s).slice(0)},f=se.compile=function(e,t){var n,v,y,m,b,r,i=[],o=[],a=k[e+" "];if(!a){t||(t=h(e)),n=t.length;while(n--)(a=Ee(t[n]))[N]?i.push(a):o.push(a);(a=k(e,(v=o,m=0<(y=i).length,b=0<v.length,r=function(e,t,n,r,i){var o,a,s,u=0,l="0",c=e&&[],f=[],d=w,p=e||b&&x.find.TAG("*",i),h=A+=null==d?1:Math.random()||.1,g=p.length;for(i&&(w=t===T||t||i);l!==g&&null!=(o=p[l]);l++){if(b&&o){a=0,t||o.ownerDocument===T||(C(o),n=!E);while(s=v[a++])if(s(o,t||T,n)){r.push(o);break}i&&(A=h)}m&&((o=!s&&o)&&u--,e&&c.push(o))}if(u+=l,m&&l!==u){a=0;while(s=y[a++])s(c,f,t,n);if(e){if(0<u)while(l--)c[l]||f[l]||(f[l]=j.call(r));f=Ce(f)}O.apply(r,f),i&&!e&&0<f.length&&1<u+y.length&&se.uniqueSort(r)}return i&&(A=h,w=d),c},m?le(r):r))).selector=e}return a},g=se.select=function(e,t,n,r){var i,o,a,s,u,l="function"==typeof e&&e,c=!r&&h(e=l.selector||e);if(n=n||[],1===c.length){if(2<(o=c[0]=c[0].slice(0)).length&&"ID"===(a=o[0]).type&&9===t.nodeType&&E&&x.relative[o[1].type]){if(!(t=(x.find.ID(a.matches[0].replace(te,ne),t)||[])[0]))return n;l&&(t=t.parentNode),e=e.slice(o.shift().value.length)}i=Q.needsContext.test(e)?0:o.length;while(i--){if(a=o[i],x.relative[s=a.type])break;if((u=x.find[s])&&(r=u(a.matches[0].replace(te,ne),ee.test(o[0].type)&&ye(t.parentNode)||t))){if(o.splice(i,1),!(e=r.length&&be(o)))return O.apply(n,r),n;break}}}return(l||f(e,c))(r,t,!E,n,!t||ee.test(e)&&ye(t.parentNode)||t),n},p.sortStable=N.split("").sort(D).join("")===N,p.detectDuplicates=!!l,C(),p.sortDetached=ce(function(e){return 1&e.compareDocumentPosition(T.createElement("fieldset"))}),ce(function(e){return e.innerHTML="<a href='#'></a>","#"===e.firstChild.getAttribute("href")})||fe("type|href|height|width",function(e,t,n){if(!n)return e.getAttribute(t,"type"===t.toLowerCase()?1:2)}),p.attributes&&ce(function(e){return e.innerHTML="<input/>",e.firstChild.setAttribute("value",""),""===e.firstChild.getAttribute("value")})||fe("value",function(e,t,n){if(!n&&"input"===e.nodeName.toLowerCase())return e.defaultValue}),ce(function(e){return null==e.getAttribute("disabled")})||fe(I,function(e,t,n){var r;if(!n)return!0===e[t]?t.toLowerCase():(r=e.getAttributeNode(t))&&r.specified?r.value:null}),se}(g);E.find=h,E.expr=h.selectors,E.expr[":"]=E.expr.pseudos,E.uniqueSort=E.unique=h.uniqueSort,E.text=h.getText,E.isXMLDoc=h.isXML,E.contains=h.contains,E.escapeSelector=h.escape;var N=function(e,t,n){var r=[],i=void 0!==n;while((e=e[t])&&9!==e.nodeType)if(1===e.nodeType){if(i&&E(e).is(n))break;r.push(e)}return r},A=function(e,t){for(var n=[];e;e=e.nextSibling)1===e.nodeType&&e!==t&&n.push(e);return n},k=E.expr.match.needsContext;function S(e,t){return e.nodeName&&e.nodeName.toLowerCase()===t.toLowerCase()}var D=/^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;function L(e,n,r){return x(n)?E.grep(e,function(e,t){return!!n.call(e,t,e)!==r}):n.nodeType?E.grep(e,function(e){return e===n!==r}):"string"!=typeof n?E.grep(e,function(e){return-1<i.call(n,e)!==r}):E.filter(n,e,r)}E.filter=function(e,t,n){var r=t[0];return n&&(e=":not("+e+")"),1===t.length&&1===r.nodeType?E.find.matchesSelector(r,e)?[r]:[]:E.find.matches(e,E.grep(t,function(e){return 1===e.nodeType}))},E.fn.extend({find:function(e){var t,n,r=this.length,i=this;if("string"!=typeof e)return this.pushStack(E(e).filter(function(){for(t=0;t<r;t++)if(E.contains(i[t],this))return!0}));for(n=this.pushStack([]),t=0;t<r;t++)E.find(e,i[t],n);return 1<r?E.uniqueSort(n):n},filter:function(e){return this.pushStack(L(this,e||[],!1))},not:function(e){return this.pushStack(L(this,e||[],!0))},is:function(e){return!!L(this,"string"==typeof e&&k.test(e)?E(e):e||[],!1).length}});var j,q=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/;(E.fn.init=function(e,t,n){var r,i;if(!e)return this;if(n=n||j,"string"==typeof e){if(!(r="<"===e[0]&&">"===e[e.length-1]&&3<=e.length?[null,e,null]:q.exec(e))||!r[1]&&t)return!t||t.jquery?(t||n).find(e):this.constructor(t).find(e);if(r[1]){if(t=t instanceof E?t[0]:t,E.merge(this,E.parseHTML(r[1],t&&t.nodeType?t.ownerDocument||t:v,!0)),D.test(r[1])&&E.isPlainObject(t))for(r in t)x(this[r])?this[r](t[r]):this.attr(r,t[r]);return this}return(i=v.getElementById(r[2]))&&(this[0]=i,this.length=1),this}return e.nodeType?(this[0]=e,this.length=1,this):x(e)?void 0!==n.ready?n.ready(e):e(E):E.makeArray(e,this)}).prototype=E.fn,j=E(v);var O=/^(?:parents|prev(?:Until|All))/,P={children:!0,contents:!0,next:!0,prev:!0};function H(e,t){while((e=e[t])&&1!==e.nodeType);return e}E.fn.extend({has:function(e){var t=E(e,this),n=t.length;return this.filter(function(){for(var e=0;e<n;e++)if(E.contains(this,t[e]))return!0})},closest:function(e,t){var n,r=0,i=this.length,o=[],a="string"!=typeof e&&E(e);if(!k.test(e))for(;r<i;r++)for(n=this[r];n&&n!==t;n=n.parentNode)if(n.nodeType<11&&(a?-1<a.index(n):1===n.nodeType&&E.find.matchesSelector(n,e))){o.push(n);break}return this.pushStack(1<o.length?E.uniqueSort(o):o)},index:function(e){return e?"string"==typeof e?i.call(E(e),this[0]):i.call(this,e.jquery?e[0]:e):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(e,t){return this.pushStack(E.uniqueSort(E.merge(this.get(),E(e,t))))},addBack:function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}}),E.each({parent:function(e){var t=e.parentNode;return t&&11!==t.nodeType?t:null},parents:function(e){return N(e,"parentNode")},parentsUntil:function(e,t,n){return N(e,"parentNode",n)},next:function(e){return H(e,"nextSibling")},prev:function(e){return H(e,"previousSibling")},nextAll:function(e){return N(e,"nextSibling")},prevAll:function(e){return N(e,"previousSibling")},nextUntil:function(e,t,n){return N(e,"nextSibling",n)},prevUntil:function(e,t,n){return N(e,"previousSibling",n)},siblings:function(e){return A((e.parentNode||{}).firstChild,e)},children:function(e){return A(e.firstChild)},contents:function(e){return"undefined"!=typeof e.contentDocument?e.contentDocument:(S(e,"template")&&(e=e.content||e),E.merge([],e.childNodes))}},function(r,i){E.fn[r]=function(e,t){var n=E.map(this,i,e);return"Until"!==r.slice(-5)&&(t=e),t&&"string"==typeof t&&(n=E.filter(t,n)),1<this.length&&(P[r]||E.uniqueSort(n),O.test(r)&&n.reverse()),this.pushStack(n)}});var I=/[^\x20\t\r\n\f]+/g;function R(e){return e}function B(e){throw e}function M(e,t,n,r){var i;try{e&&x(i=e.promise)?i.call(e).done(t).fail(n):e&&x(i=e.then)?i.call(e,t,n):t.apply(void 0,[e].slice(r))}catch(e){n.apply(void 0,[e])}}E.Callbacks=function(r){var e,n;r="string"==typeof r?(e=r,n={},E.each(e.match(I)||[],function(e,t){n[t]=!0}),n):E.extend({},r);var i,t,o,a,s=[],u=[],l=-1,c=function(){for(a=a||r.once,o=i=!0;u.length;l=-1){t=u.shift();while(++l<s.length)!1===s[l].apply(t[0],t[1])&&r.stopOnFalse&&(l=s.length,t=!1)}r.memory||(t=!1),i=!1,a&&(s=t?[]:"")},f={add:function(){return s&&(t&&!i&&(l=s.length-1,u.push(t)),function n(e){E.each(e,function(e,t){x(t)?r.unique&&f.has(t)||s.push(t):t&&t.length&&"string"!==T(t)&&n(t)})}(arguments),t&&!i&&c()),this},remove:function(){return E.each(arguments,function(e,t){var n;while(-1<(n=E.inArray(t,s,n)))s.splice(n,1),n<=l&&l--}),this},has:function(e){return e?-1<E.inArray(e,s):0<s.length},empty:function(){return s&&(s=[]),this},disable:function(){return a=u=[],s=t="",this},disabled:function(){return!s},lock:function(){return a=u=[],t||i||(s=t=""),this},locked:function(){return!!a},fireWith:function(e,t){return a||(t=[e,(t=t||[]).slice?t.slice():t],u.push(t),i||c()),this},fire:function(){return f.fireWith(this,arguments),this},fired:function(){return!!o}};return f},E.extend({Deferred:function(e){var o=[["notify","progress",E.Callbacks("memory"),E.Callbacks("memory"),2],["resolve","done",E.Callbacks("once memory"),E.Callbacks("once memory"),0,"resolved"],["reject","fail",E.Callbacks("once memory"),E.Callbacks("once memory"),1,"rejected"]],i="pending",a={state:function(){return i},always:function(){return s.done(arguments).fail(arguments),this},"catch":function(e){return a.then(null,e)},pipe:function(){var i=arguments;return E.Deferred(function(r){E.each(o,function(e,t){var n=x(i[t[4]])&&i[t[4]];s[t[1]](function(){var e=n&&n.apply(this,arguments);e&&x(e.promise)?e.promise().progress(r.notify).done(r.resolve).fail(r.reject):r[t[0]+"With"](this,n?[e]:arguments)})}),i=null}).promise()},then:function(t,n,r){var u=0;function l(i,o,a,s){return function(){var n=this,r=arguments,e=function(){var e,t;if(!(i<u)){if((e=a.apply(n,r))===o.promise())throw new TypeError("Thenable self-resolution");t=e&&("object"==typeof e||"function"==typeof e)&&e.then,x(t)?s?t.call(e,l(u,o,R,s),l(u,o,B,s)):(u++,t.call(e,l(u,o,R,s),l(u,o,B,s),l(u,o,R,o.notifyWith))):(a!==R&&(n=void 0,r=[e]),(s||o.resolveWith)(n,r))}},t=s?e:function(){try{e()}catch(e){E.Deferred.exceptionHook&&E.Deferred.exceptionHook(e,t.stackTrace),u<=i+1&&(a!==B&&(n=void 0,r=[e]),o.rejectWith(n,r))}};i?t():(E.Deferred.getStackHook&&(t.stackTrace=E.Deferred.getStackHook()),g.setTimeout(t))}}return E.Deferred(function(e){o[0][3].add(l(0,e,x(r)?r:R,e.notifyWith)),o[1][3].add(l(0,e,x(t)?t:R)),o[2][3].add(l(0,e,x(n)?n:B))}).promise()},promise:function(e){return null!=e?E.extend(e,a):a}},s={};return E.each(o,function(e,t){var n=t[2],r=t[5];a[t[1]]=n.add,r&&n.add(function(){i=r},o[3-e][2].disable,o[3-e][3].disable,o[0][2].lock,o[0][3].lock),n.add(t[3].fire),s[t[0]]=function(){return s[t[0]+"With"](this===s?void 0:this,arguments),this},s[t[0]+"With"]=n.fireWith}),a.promise(s),e&&e.call(s,s),s},when:function(e){var n=arguments.length,t=n,r=Array(t),i=s.call(arguments),o=E.Deferred(),a=function(t){return function(e){r[t]=this,i[t]=1<arguments.length?s.call(arguments):e,--n||o.resolveWith(r,i)}};if(n<=1&&(M(e,o.done(a(t)).resolve,o.reject,!n),"pending"===o.state()||x(i[t]&&i[t].then)))return o.then();while(t--)M(i[t],a(t),o.reject);return o.promise()}});var W=/^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;E.Deferred.exceptionHook=function(e,t){g.console&&g.console.warn&&e&&W.test(e.name)&&g.console.warn("jQuery.Deferred exception: "+e.message,e.stack,t)},E.readyException=function(e){g.setTimeout(function(){throw e})};var $=E.Deferred();function F(){v.removeEventListener("DOMContentLoaded",F),g.removeEventListener("load",F),E.ready()}E.fn.ready=function(e){return $.then(e)["catch"](function(e){E.readyException(e)}),this},E.extend({isReady:!1,readyWait:1,ready:function(e){(!0===e?--E.readyWait:E.isReady)||(E.isReady=!0)!==e&&0<--E.readyWait||$.resolveWith(v,[E])}}),E.ready.then=$.then,"complete"===v.readyState||"loading"!==v.readyState&&!v.documentElement.doScroll?g.setTimeout(E.ready):(v.addEventListener("DOMContentLoaded",F),g.addEventListener("load",F));var z=function(e,t,n,r,i,o,a){var s=0,u=e.length,l=null==n;if("object"===T(n))for(s in i=!0,n)z(e,t,s,n[s],!0,o,a);else if(void 0!==r&&(i=!0,x(r)||(a=!0),l&&(a?(t.call(e,r),t=null):(l=t,t=function(e,t,n){return l.call(E(e),n)})),t))for(;s<u;s++)t(e[s],n,a?r:r.call(e[s],s,t(e[s],n)));return i?e:l?t.call(e):u?t(e[0],n):o},_=/^-ms-/,U=/-([a-z])/g;function V(e,t){return t.toUpperCase()}function X(e){return e.replace(_,"ms-").replace(U,V)}var Q=function(e){return 1===e.nodeType||9===e.nodeType||!+e.nodeType};function Y(){this.expando=E.expando+Y.uid++}Y.uid=1,Y.prototype={cache:function(e){var t=e[this.expando];return t||(t={},Q(e)&&(e.nodeType?e[this.expando]=t:Object.defineProperty(e,this.expando,{value:t,configurable:!0}))),t},set:function(e,t,n){var r,i=this.cache(e);if("string"==typeof t)i[X(t)]=n;else for(r in t)i[X(r)]=t[r];return i},get:function(e,t){return void 0===t?this.cache(e):e[this.expando]&&e[this.expando][X(t)]},access:function(e,t,n){return void 0===t||t&&"string"==typeof t&&void 0===n?this.get(e,t):(this.set(e,t,n),void 0!==n?n:t)},remove:function(e,t){var n,r=e[this.expando];if(void 0!==r){if(void 0!==t){n=(t=Array.isArray(t)?t.map(X):(t=X(t))in r?[t]:t.match(I)||[]).length;while(n--)delete r[t[n]]}(void 0===t||E.isEmptyObject(r))&&(e.nodeType?e[this.expando]=void 0:delete e[this.expando])}},hasData:function(e){var t=e[this.expando];return void 0!==t&&!E.isEmptyObject(t)}};var G=new Y,K=new Y,J=/^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,Z=/[A-Z]/g;function ee(e,t,n){var r,i;if(void 0===n&&1===e.nodeType)if(r="data-"+t.replace(Z,"-$&").toLowerCase(),"string"==typeof(n=e.getAttribute(r))){try{n="true"===(i=n)||"false"!==i&&("null"===i?null:i===+i+""?+i:J.test(i)?JSON.parse(i):i)}catch(e){}K.set(e,t,n)}else n=void 0;return n}E.extend({hasData:function(e){return K.hasData(e)||G.hasData(e)},data:function(e,t,n){return K.access(e,t,n)},removeData:function(e,t){K.remove(e,t)},_data:function(e,t,n){return G.access(e,t,n)},_removeData:function(e,t){G.remove(e,t)}}),E.fn.extend({data:function(n,e){var t,r,i,o=this[0],a=o&&o.attributes;if(void 0===n){if(this.length&&(i=K.get(o),1===o.nodeType&&!G.get(o,"hasDataAttrs"))){t=a.length;while(t--)a[t]&&0===(r=a[t].name).indexOf("data-")&&(r=X(r.slice(5)),ee(o,r,i[r]));G.set(o,"hasDataAttrs",!0)}return i}return"object"==typeof n?this.each(function(){K.set(this,n)}):z(this,function(e){var t;if(o&&void 0===e)return void 0!==(t=K.get(o,n))?t:void 0!==(t=ee(o,n))?t:void 0;this.each(function(){K.set(this,n,e)})},null,e,1<arguments.length,null,!0)},removeData:function(e){return this.each(function(){K.remove(this,e)})}}),E.extend({queue:function(e,t,n){var r;if(e)return t=(t||"fx")+"queue",r=G.get(e,t),n&&(!r||Array.isArray(n)?r=G.access(e,t,E.makeArray(n)):r.push(n)),r||[]},dequeue:function(e,t){t=t||"fx";var n=E.queue(e,t),r=n.length,i=n.shift(),o=E._queueHooks(e,t);"inprogress"===i&&(i=n.shift(),r--),i&&("fx"===t&&n.unshift("inprogress"),delete o.stop,i.call(e,function(){E.dequeue(e,t)},o)),!r&&o&&o.empty.fire()},_queueHooks:function(e,t){var n=t+"queueHooks";return G.get(e,n)||G.access(e,n,{empty:E.Callbacks("once memory").add(function(){G.remove(e,[t+"queue",n])})})}}),E.fn.extend({queue:function(t,n){var e=2;return"string"!=typeof t&&(n=t,t="fx",e--),arguments.length<e?E.queue(this[0],t):void 0===n?this:this.each(function(){var e=E.queue(this,t,n);E._queueHooks(this,t),"fx"===t&&"inprogress"!==e[0]&&E.dequeue(this,t)})},dequeue:function(e){return this.each(function(){E.dequeue(this,e)})},clearQueue:function(e){return this.queue(e||"fx",[])},promise:function(e,t){var n,r=1,i=E.Deferred(),o=this,a=this.length,s=function(){--r||i.resolveWith(o,[o])};"string"!=typeof e&&(t=e,e=void 0),e=e||"fx";while(a--)(n=G.get(o[a],e+"queueHooks"))&&n.empty&&(r++,n.empty.add(s));return s(),i.promise(t)}});var te=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,ne=new RegExp("^(?:([+-])=|)("+te+")([a-z%]*)$","i"),re=["Top","Right","Bottom","Left"],ie=v.documentElement,oe=function(e){return E.contains(e.ownerDocument,e)},ae={composed:!0};ie.getRootNode&&(oe=function(e){return E.contains(e.ownerDocument,e)||e.getRootNode(ae)===e.ownerDocument});var se=function(e,t){return"none"===(e=t||e).style.display||""===e.style.display&&oe(e)&&"none"===E.css(e,"display")},ue=function(e,t,n,r){var i,o,a={};for(o in t)a[o]=e.style[o],e.style[o]=t[o];for(o in i=n.apply(e,r||[]),t)e.style[o]=a[o];return i};var le={};function ce(e,t){for(var n,r,i,o,a,s,u,l=[],c=0,f=e.length;c<f;c++)(r=e[c]).style&&(n=r.style.display,t?("none"===n&&(l[c]=G.get(r,"display")||null,l[c]||(r.style.display="")),""===r.style.display&&se(r)&&(l[c]=(u=a=o=void 0,a=(i=r).ownerDocument,s=i.nodeName,(u=le[s])||(o=a.body.appendChild(a.createElement(s)),u=E.css(o,"display"),o.parentNode.removeChild(o),"none"===u&&(u="block"),le[s]=u)))):"none"!==n&&(l[c]="none",G.set(r,"display",n)));for(c=0;c<f;c++)null!=l[c]&&(e[c].style.display=l[c]);return e}E.fn.extend({show:function(){return ce(this,!0)},hide:function(){return ce(this)},toggle:function(e){return"boolean"==typeof e?e?this.show():this.hide():this.each(function(){se(this)?E(this).show():E(this).hide()})}});var fe=/^(?:checkbox|radio)$/i,de=/<([a-z][^\/\0>\x20\t\r\n\f]*)/i,pe=/^$|^module$|\/(?:java|ecma)script/i,he={option:[1,"<select multiple='multiple'>","</select>"],thead:[1,"<table>","</table>"],col:[2,"<table><colgroup>","</colgroup></table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:[0,"",""]};function ge(e,t){var n;return n="undefined"!=typeof e.getElementsByTagName?e.getElementsByTagName(t||"*"):"undefined"!=typeof e.querySelectorAll?e.querySelectorAll(t||"*"):[],void 0===t||t&&S(e,t)?E.merge([e],n):n}function ve(e,t){for(var n=0,r=e.length;n<r;n++)G.set(e[n],"globalEval",!t||G.get(t[n],"globalEval"))}he.optgroup=he.option,he.tbody=he.tfoot=he.colgroup=he.caption=he.thead,he.th=he.td;var ye,me,be=/<|&#?\w+;/;function xe(e,t,n,r,i){for(var o,a,s,u,l,c,f=t.createDocumentFragment(),d=[],p=0,h=e.length;p<h;p++)if((o=e[p])||0===o)if("object"===T(o))E.merge(d,o.nodeType?[o]:o);else if(be.test(o)){a=a||f.appendChild(t.createElement("div")),s=(de.exec(o)||["",""])[1].toLowerCase(),u=he[s]||he._default,a.innerHTML=u[1]+E.htmlPrefilter(o)+u[2],c=u[0];while(c--)a=a.lastChild;E.merge(d,a.childNodes),(a=f.firstChild).textContent=""}else d.push(t.createTextNode(o));f.textContent="",p=0;while(o=d[p++])if(r&&-1<E.inArray(o,r))i&&i.push(o);else if(l=oe(o),a=ge(f.appendChild(o),"script"),l&&ve(a),n){c=0;while(o=a[c++])pe.test(o.type||"")&&n.push(o)}return f}ye=v.createDocumentFragment().appendChild(v.createElement("div")),(me=v.createElement("input")).setAttribute("type","radio"),me.setAttribute("checked","checked"),me.setAttribute("name","t"),ye.appendChild(me),b.checkClone=ye.cloneNode(!0).cloneNode(!0).lastChild.checked,ye.innerHTML="<textarea>x</textarea>",b.noCloneChecked=!!ye.cloneNode(!0).lastChild.defaultValue;var we=/^key/,Ce=/^(?:mouse|pointer|contextmenu|drag|drop)|click/,Te=/^([^.]*)(?:\.(.+)|)/;function Ee(){return!0}function Ne(){return!1}function Ae(e,t){return e===function(){try{return v.activeElement}catch(e){}}()==("focus"===t)}function ke(e,t,n,r,i,o){var a,s;if("object"==typeof t){for(s in"string"!=typeof n&&(r=r||n,n=void 0),t)ke(e,s,n,r,t[s],o);return e}if(null==r&&null==i?(i=n,r=n=void 0):null==i&&("string"==typeof n?(i=r,r=void 0):(i=r,r=n,n=void 0)),!1===i)i=Ne;else if(!i)return e;return 1===o&&(a=i,(i=function(e){return E().off(e),a.apply(this,arguments)}).guid=a.guid||(a.guid=E.guid++)),e.each(function(){E.event.add(this,t,i,r,n)})}function Se(e,i,o){o?(G.set(e,i,!1),E.event.add(e,i,{namespace:!1,handler:function(e){var t,n,r=G.get(this,i);if(1&e.isTrigger&&this[i]){if(r.length)(E.event.special[i]||{}).delegateType&&e.stopPropagation();else if(r=s.call(arguments),G.set(this,i,r),t=o(this,i),this[i](),r!==(n=G.get(this,i))||t?G.set(this,i,!1):n={},r!==n)return e.stopImmediatePropagation(),e.preventDefault(),n.value}else r.length&&(G.set(this,i,{value:E.event.trigger(E.extend(r[0],E.Event.prototype),r.slice(1),this)}),e.stopImmediatePropagation())}})):void 0===G.get(e,i)&&E.event.add(e,i,Ee)}E.event={global:{},add:function(t,e,n,r,i){var o,a,s,u,l,c,f,d,p,h,g,v=G.get(t);if(v){n.handler&&(n=(o=n).handler,i=o.selector),i&&E.find.matchesSelector(ie,i),n.guid||(n.guid=E.guid++),(u=v.events)||(u=v.events={}),(a=v.handle)||(a=v.handle=function(e){return"undefined"!=typeof E&&E.event.triggered!==e.type?E.event.dispatch.apply(t,arguments):void 0}),l=(e=(e||"").match(I)||[""]).length;while(l--)p=g=(s=Te.exec(e[l])||[])[1],h=(s[2]||"").split(".").sort(),p&&(f=E.event.special[p]||{},p=(i?f.delegateType:f.bindType)||p,f=E.event.special[p]||{},c=E.extend({type:p,origType:g,data:r,handler:n,guid:n.guid,selector:i,needsContext:i&&E.expr.match.needsContext.test(i),namespace:h.join(".")},o),(d=u[p])||((d=u[p]=[]).delegateCount=0,f.setup&&!1!==f.setup.call(t,r,h,a)||t.addEventListener&&t.addEventListener(p,a)),f.add&&(f.add.call(t,c),c.handler.guid||(c.handler.guid=n.guid)),i?d.splice(d.delegateCount++,0,c):d.push(c),E.event.global[p]=!0)}},remove:function(e,t,n,r,i){var o,a,s,u,l,c,f,d,p,h,g,v=G.hasData(e)&&G.get(e);if(v&&(u=v.events)){l=(t=(t||"").match(I)||[""]).length;while(l--)if(p=g=(s=Te.exec(t[l])||[])[1],h=(s[2]||"").split(".").sort(),p){f=E.event.special[p]||{},d=u[p=(r?f.delegateType:f.bindType)||p]||[],s=s[2]&&new RegExp("(^|\\.)"+h.join("\\.(?:.*\\.|)")+"(\\.|$)"),a=o=d.length;while(o--)c=d[o],!i&&g!==c.origType||n&&n.guid!==c.guid||s&&!s.test(c.namespace)||r&&r!==c.selector&&("**"!==r||!c.selector)||(d.splice(o,1),c.selector&&d.delegateCount--,f.remove&&f.remove.call(e,c));a&&!d.length&&(f.teardown&&!1!==f.teardown.call(e,h,v.handle)||E.removeEvent(e,p,v.handle),delete u[p])}else for(p in u)E.event.remove(e,p+t[l],n,r,!0);E.isEmptyObject(u)&&G.remove(e,"handle events")}},dispatch:function(e){var t,n,r,i,o,a,s=E.event.fix(e),u=new Array(arguments.length),l=(G.get(this,"events")||{})[s.type]||[],c=E.event.special[s.type]||{};for(u[0]=s,t=1;t<arguments.length;t++)u[t]=arguments[t];if(s.delegateTarget=this,!c.preDispatch||!1!==c.preDispatch.call(this,s)){a=E.event.handlers.call(this,s,l),t=0;while((i=a[t++])&&!s.isPropagationStopped()){s.currentTarget=i.elem,n=0;while((o=i.handlers[n++])&&!s.isImmediatePropagationStopped())s.rnamespace&&!1!==o.namespace&&!s.rnamespace.test(o.namespace)||(s.handleObj=o,s.data=o.data,void 0!==(r=((E.event.special[o.origType]||{}).handle||o.handler).apply(i.elem,u))&&!1===(s.result=r)&&(s.preventDefault(),s.stopPropagation()))}return c.postDispatch&&c.postDispatch.call(this,s),s.result}},handlers:function(e,t){var n,r,i,o,a,s=[],u=t.delegateCount,l=e.target;if(u&&l.nodeType&&!("click"===e.type&&1<=e.button))for(;l!==this;l=l.parentNode||this)if(1===l.nodeType&&("click"!==e.type||!0!==l.disabled)){for(o=[],a={},n=0;n<u;n++)void 0===a[i=(r=t[n]).selector+" "]&&(a[i]=r.needsContext?-1<E(i,this).index(l):E.find(i,this,null,[l]).length),a[i]&&o.push(r);o.length&&s.push({elem:l,handlers:o})}return l=this,u<t.length&&s.push({elem:l,handlers:t.slice(u)}),s},addProp:function(t,e){Object.defineProperty(E.Event.prototype,t,{enumerable:!0,configurable:!0,get:x(e)?function(){if(this.originalEvent)return e(this.originalEvent)}:function(){if(this.originalEvent)return this.originalEvent[t]},set:function(e){Object.defineProperty(this,t,{enumerable:!0,configurable:!0,writable:!0,value:e})}})},fix:function(e){return e[E.expando]?e:new E.Event(e)},special:{load:{noBubble:!0},click:{setup:function(e){var t=this||e;return fe.test(t.type)&&t.click&&S(t,"input")&&Se(t,"click",Ee),!1},trigger:function(e){var t=this||e;return fe.test(t.type)&&t.click&&S(t,"input")&&Se(t,"click"),!0},_default:function(e){var t=e.target;return fe.test(t.type)&&t.click&&S(t,"input")&&G.get(t,"click")||S(t,"a")}},beforeunload:{postDispatch:function(e){void 0!==e.result&&e.originalEvent&&(e.originalEvent.returnValue=e.result)}}}},E.removeEvent=function(e,t,n){e.removeEventListener&&e.removeEventListener(t,n)},E.Event=function(e,t){if(!(this instanceof E.Event))return new E.Event(e,t);e&&e.type?(this.originalEvent=e,this.type=e.type,this.isDefaultPrevented=e.defaultPrevented||void 0===e.defaultPrevented&&!1===e.returnValue?Ee:Ne,this.target=e.target&&3===e.target.nodeType?e.target.parentNode:e.target,this.currentTarget=e.currentTarget,this.relatedTarget=e.relatedTarget):this.type=e,t&&E.extend(this,t),this.timeStamp=e&&e.timeStamp||Date.now(),this[E.expando]=!0},E.Event.prototype={constructor:E.Event,isDefaultPrevented:Ne,isPropagationStopped:Ne,isImmediatePropagationStopped:Ne,isSimulated:!1,preventDefault:function(){var e=this.originalEvent;this.isDefaultPrevented=Ee,e&&!this.isSimulated&&e.preventDefault()},stopPropagation:function(){var e=this.originalEvent;this.isPropagationStopped=Ee,e&&!this.isSimulated&&e.stopPropagation()},stopImmediatePropagation:function(){var e=this.originalEvent;this.isImmediatePropagationStopped=Ee,e&&!this.isSimulated&&e.stopImmediatePropagation(),this.stopPropagation()}},E.each({altKey:!0,bubbles:!0,cancelable:!0,changedTouches:!0,ctrlKey:!0,detail:!0,eventPhase:!0,metaKey:!0,pageX:!0,pageY:!0,shiftKey:!0,view:!0,"char":!0,code:!0,charCode:!0,key:!0,keyCode:!0,button:!0,buttons:!0,clientX:!0,clientY:!0,offsetX:!0,offsetY:!0,pointerId:!0,pointerType:!0,screenX:!0,screenY:!0,targetTouches:!0,toElement:!0,touches:!0,which:function(e){var t=e.button;return null==e.which&&we.test(e.type)?null!=e.charCode?e.charCode:e.keyCode:!e.which&&void 0!==t&&Ce.test(e.type)?1&t?1:2&t?3:4&t?2:0:e.which}},E.event.addProp),E.each({focus:"focusin",blur:"focusout"},function(e,t){E.event.special[e]={setup:function(){return Se(this,e,Ae),!1},trigger:function(){return Se(this,e),!0},delegateType:t}}),E.each({mouseenter:"mouseover",mouseleave:"mouseout",pointerenter:"pointerover",pointerleave:"pointerout"},function(e,i){E.event.special[e]={delegateType:i,bindType:i,handle:function(e){var t,n=e.relatedTarget,r=e.handleObj;return n&&(n===this||E.contains(this,n))||(e.type=r.origType,t=r.handler.apply(this,arguments),e.type=i),t}}}),E.fn.extend({on:function(e,t,n,r){return ke(this,e,t,n,r)},one:function(e,t,n,r){return ke(this,e,t,n,r,1)},off:function(e,t,n){var r,i;if(e&&e.preventDefault&&e.handleObj)return r=e.handleObj,E(e.delegateTarget).off(r.namespace?r.origType+"."+r.namespace:r.origType,r.selector,r.handler),this;if("object"==typeof e){for(i in e)this.off(i,t,e[i]);return this}return!1!==t&&"function"!=typeof t||(n=t,t=void 0),!1===n&&(n=Ne),this.each(function(){E.event.remove(this,e,n,t)})}});var De=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi,Le=/<script|<style|<link/i,je=/checked\s*(?:[^=]|=\s*.checked.)/i,qe=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;function Oe(e,t){return S(e,"table")&&S(11!==t.nodeType?t:t.firstChild,"tr")&&E(e).children("tbody")[0]||e}function Pe(e){return e.type=(null!==e.getAttribute("type"))+"/"+e.type,e}function He(e){return"true/"===(e.type||"").slice(0,5)?e.type=e.type.slice(5):e.removeAttribute("type"),e}function Ie(e,t){var n,r,i,o,a,s,u,l;if(1===t.nodeType){if(G.hasData(e)&&(o=G.access(e),a=G.set(t,o),l=o.events))for(i in delete a.handle,a.events={},l)for(n=0,r=l[i].length;n<r;n++)E.event.add(t,i,l[i][n]);K.hasData(e)&&(s=K.access(e),u=E.extend({},s),K.set(t,u))}}function Re(n,r,i,o){r=y.apply([],r);var e,t,a,s,u,l,c=0,f=n.length,d=f-1,p=r[0],h=x(p);if(h||1<f&&"string"==typeof p&&!b.checkClone&&je.test(p))return n.each(function(e){var t=n.eq(e);h&&(r[0]=p.call(this,e,t.html())),Re(t,r,i,o)});if(f&&(t=(e=xe(r,n[0].ownerDocument,!1,n,o)).firstChild,1===e.childNodes.length&&(e=t),t||o)){for(s=(a=E.map(ge(e,"script"),Pe)).length;c<f;c++)u=e,c!==d&&(u=E.clone(u,!0,!0),s&&E.merge(a,ge(u,"script"))),i.call(n[c],u,c);if(s)for(l=a[a.length-1].ownerDocument,E.map(a,He),c=0;c<s;c++)u=a[c],pe.test(u.type||"")&&!G.access(u,"globalEval")&&E.contains(l,u)&&(u.src&&"module"!==(u.type||"").toLowerCase()?E._evalUrl&&!u.noModule&&E._evalUrl(u.src,{nonce:u.nonce||u.getAttribute("nonce")}):C(u.textContent.replace(qe,""),u,l))}return n}function Be(e,t,n){for(var r,i=t?E.filter(t,e):e,o=0;null!=(r=i[o]);o++)n||1!==r.nodeType||E.cleanData(ge(r)),r.parentNode&&(n&&oe(r)&&ve(ge(r,"script")),r.parentNode.removeChild(r));return e}E.extend({htmlPrefilter:function(e){return e.replace(De,"<$1></$2>")},clone:function(e,t,n){var r,i,o,a,s,u,l,c=e.cloneNode(!0),f=oe(e);if(!(b.noCloneChecked||1!==e.nodeType&&11!==e.nodeType||E.isXMLDoc(e)))for(a=ge(c),r=0,i=(o=ge(e)).length;r<i;r++)s=o[r],u=a[r],void 0,"input"===(l=u.nodeName.toLowerCase())&&fe.test(s.type)?u.checked=s.checked:"input"!==l&&"textarea"!==l||(u.defaultValue=s.defaultValue);if(t)if(n)for(o=o||ge(e),a=a||ge(c),r=0,i=o.length;r<i;r++)Ie(o[r],a[r]);else Ie(e,c);return 0<(a=ge(c,"script")).length&&ve(a,!f&&ge(e,"script")),c},cleanData:function(e){for(var t,n,r,i=E.event.special,o=0;void 0!==(n=e[o]);o++)if(Q(n)){if(t=n[G.expando]){if(t.events)for(r in t.events)i[r]?E.event.remove(n,r):E.removeEvent(n,r,t.handle);n[G.expando]=void 0}n[K.expando]&&(n[K.expando]=void 0)}}}),E.fn.extend({detach:function(e){return Be(this,e,!0)},remove:function(e){return Be(this,e)},text:function(e){return z(this,function(e){return void 0===e?E.text(this):this.empty().each(function(){1!==this.nodeType&&11!==this.nodeType&&9!==this.nodeType||(this.textContent=e)})},null,e,arguments.length)},append:function(){return Re(this,arguments,function(e){1!==this.nodeType&&11!==this.nodeType&&9!==this.nodeType||Oe(this,e).appendChild(e)})},prepend:function(){return Re(this,arguments,function(e){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var t=Oe(this,e);t.insertBefore(e,t.firstChild)}})},before:function(){return Re(this,arguments,function(e){this.parentNode&&this.parentNode.insertBefore(e,this)})},after:function(){return Re(this,arguments,function(e){this.parentNode&&this.parentNode.insertBefore(e,this.nextSibling)})},empty:function(){for(var e,t=0;null!=(e=this[t]);t++)1===e.nodeType&&(E.cleanData(ge(e,!1)),e.textContent="");return this},clone:function(e,t){return e=null!=e&&e,t=null==t?e:t,this.map(function(){return E.clone(this,e,t)})},html:function(e){return z(this,function(e){var t=this[0]||{},n=0,r=this.length;if(void 0===e&&1===t.nodeType)return t.innerHTML;if("string"==typeof e&&!Le.test(e)&&!he[(de.exec(e)||["",""])[1].toLowerCase()]){e=E.htmlPrefilter(e);try{for(;n<r;n++)1===(t=this[n]||{}).nodeType&&(E.cleanData(ge(t,!1)),t.innerHTML=e);t=0}catch(e){}}t&&this.empty().append(e)},null,e,arguments.length)},replaceWith:function(){var n=[];return Re(this,arguments,function(e){var t=this.parentNode;E.inArray(this,n)<0&&(E.cleanData(ge(this)),t&&t.replaceChild(e,this))},n)}}),E.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(e,a){E.fn[e]=function(e){for(var t,n=[],r=E(e),i=r.length-1,o=0;o<=i;o++)t=o===i?this:this.clone(!0),E(r[o])[a](t),u.apply(n,t.get());return this.pushStack(n)}});var Me=new RegExp("^("+te+")(?!px)[a-z%]+$","i"),We=function(e){var t=e.ownerDocument.defaultView;return t&&t.opener||(t=g),t.getComputedStyle(e)},$e=new RegExp(re.join("|"),"i");function Fe(e,t,n){var r,i,o,a,s=e.style;return(n=n||We(e))&&(""!==(a=n.getPropertyValue(t)||n[t])||oe(e)||(a=E.style(e,t)),!b.pixelBoxStyles()&&Me.test(a)&&$e.test(t)&&(r=s.width,i=s.minWidth,o=s.maxWidth,s.minWidth=s.maxWidth=s.width=a,a=n.width,s.width=r,s.minWidth=i,s.maxWidth=o)),void 0!==a?a+"":a}function ze(e,t){return{get:function(){if(!e())return(this.get=t).apply(this,arguments);delete this.get}}}!function(){function e(){if(u){s.style.cssText="position:absolute;left:-11111px;width:60px;margin-top:1px;padding:0;border:0",u.style.cssText="position:relative;display:block;box-sizing:border-box;overflow:scroll;margin:auto;border:1px;padding:1px;width:60%;top:1%",ie.appendChild(s).appendChild(u);var e=g.getComputedStyle(u);n="1%"!==e.top,a=12===t(e.marginLeft),u.style.right="60%",o=36===t(e.right),r=36===t(e.width),u.style.position="absolute",i=12===t(u.offsetWidth/3),ie.removeChild(s),u=null}}function t(e){return Math.round(parseFloat(e))}var n,r,i,o,a,s=v.createElement("div"),u=v.createElement("div");u.style&&(u.style.backgroundClip="content-box",u.cloneNode(!0).style.backgroundClip="",b.clearCloneStyle="content-box"===u.style.backgroundClip,E.extend(b,{boxSizingReliable:function(){return e(),r},pixelBoxStyles:function(){return e(),o},pixelPosition:function(){return e(),n},reliableMarginLeft:function(){return e(),a},scrollboxSize:function(){return e(),i}}))}();var _e=["Webkit","Moz","ms"],Ue=v.createElement("div").style,Ve={};function Xe(e){var t=E.cssProps[e]||Ve[e];return t||(e in Ue?e:Ve[e]=function(e){var t=e[0].toUpperCase()+e.slice(1),n=_e.length;while(n--)if((e=_e[n]+t)in Ue)return e}(e)||e)}var Qe,Ye,Ge=/^(none|table(?!-c[ea]).+)/,Ke=/^--/,Je={position:"absolute",visibility:"hidden",display:"block"},Ze={letterSpacing:"0",fontWeight:"400"};function et(e,t,n){var r=ne.exec(t);return r?Math.max(0,r[2]-(n||0))+(r[3]||"px"):t}function tt(e,t,n,r,i,o){var a="width"===t?1:0,s=0,u=0;if(n===(r?"border":"content"))return 0;for(;a<4;a+=2)"margin"===n&&(u+=E.css(e,n+re[a],!0,i)),r?("content"===n&&(u-=E.css(e,"padding"+re[a],!0,i)),"margin"!==n&&(u-=E.css(e,"border"+re[a]+"Width",!0,i))):(u+=E.css(e,"padding"+re[a],!0,i),"padding"!==n?u+=E.css(e,"border"+re[a]+"Width",!0,i):s+=E.css(e,"border"+re[a]+"Width",!0,i));return!r&&0<=o&&(u+=Math.max(0,Math.ceil(e["offset"+t[0].toUpperCase()+t.slice(1)]-o-u-s-.5))||0),u}function nt(e,t,n){var r=We(e),i=(!b.boxSizingReliable()||n)&&"border-box"===E.css(e,"boxSizing",!1,r),o=i,a=Fe(e,t,r),s="offset"+t[0].toUpperCase()+t.slice(1);if(Me.test(a)){if(!n)return a;a="auto"}return(!b.boxSizingReliable()&&i||"auto"===a||!parseFloat(a)&&"inline"===E.css(e,"display",!1,r))&&e.getClientRects().length&&(i="border-box"===E.css(e,"boxSizing",!1,r),(o=s in e)&&(a=e[s])),(a=parseFloat(a)||0)+tt(e,t,n||(i?"border":"content"),o,r,a)+"px"}E.extend({cssHooks:{opacity:{get:function(e,t){if(t){var n=Fe(e,"opacity");return""===n?"1":n}}}},cssNumber:{animationIterationCount:!0,columnCount:!0,fillOpacity:!0,flexGrow:!0,flexShrink:!0,fontWeight:!0,gridArea:!0,gridColumn:!0,gridColumnEnd:!0,gridColumnStart:!0,gridRow:!0,gridRowEnd:!0,gridRowStart:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{},style:function(e,t,n,r){if(e&&3!==e.nodeType&&8!==e.nodeType&&e.style){var i,o,a,s=X(t),u=Ke.test(t),l=e.style;if(u||(t=Xe(s)),a=E.cssHooks[t]||E.cssHooks[s],void 0===n)return a&&"get"in a&&void 0!==(i=a.get(e,!1,r))?i:l[t];"string"===(o=typeof n)&&(i=ne.exec(n))&&i[1]&&(n=function(e,t,n,r){var i,o,a=20,s=r?function(){return r.cur()}:function(){return E.css(e,t,"")},u=s(),l=n&&n[3]||(E.cssNumber[t]?"":"px"),c=e.nodeType&&(E.cssNumber[t]||"px"!==l&&+u)&&ne.exec(E.css(e,t));if(c&&c[3]!==l){u/=2,l=l||c[3],c=+u||1;while(a--)E.style(e,t,c+l),(1-o)*(1-(o=s()/u||.5))<=0&&(a=0),c/=o;c*=2,E.style(e,t,c+l),n=n||[]}return n&&(c=+c||+u||0,i=n[1]?c+(n[1]+1)*n[2]:+n[2],r&&(r.unit=l,r.start=c,r.end=i)),i}(e,t,i),o="number"),null!=n&&n==n&&("number"!==o||u||(n+=i&&i[3]||(E.cssNumber[s]?"":"px")),b.clearCloneStyle||""!==n||0!==t.indexOf("background")||(l[t]="inherit"),a&&"set"in a&&void 0===(n=a.set(e,n,r))||(u?l.setProperty(t,n):l[t]=n))}},css:function(e,t,n,r){var i,o,a,s=X(t);return Ke.test(t)||(t=Xe(s)),(a=E.cssHooks[t]||E.cssHooks[s])&&"get"in a&&(i=a.get(e,!0,n)),void 0===i&&(i=Fe(e,t,r)),"normal"===i&&t in Ze&&(i=Ze[t]),""===n||n?(o=parseFloat(i),!0===n||isFinite(o)?o||0:i):i}}),E.each(["height","width"],function(e,u){E.cssHooks[u]={get:function(e,t,n){if(t)return!Ge.test(E.css(e,"display"))||e.getClientRects().length&&e.getBoundingClientRect().width?nt(e,u,n):ue(e,Je,function(){return nt(e,u,n)})},set:function(e,t,n){var r,i=We(e),o=!b.scrollboxSize()&&"absolute"===i.position,a=(o||n)&&"border-box"===E.css(e,"boxSizing",!1,i),s=n?tt(e,u,n,a,i):0;return a&&o&&(s-=Math.ceil(e["offset"+u[0].toUpperCase()+u.slice(1)]-parseFloat(i[u])-tt(e,u,"border",!1,i)-.5)),s&&(r=ne.exec(t))&&"px"!==(r[3]||"px")&&(e.style[u]=t,t=E.css(e,u)),et(0,t,s)}}}),E.cssHooks.marginLeft=ze(b.reliableMarginLeft,function(e,t){if(t)return(parseFloat(Fe(e,"marginLeft"))||e.getBoundingClientRect().left-ue(e,{marginLeft:0},function(){return e.getBoundingClientRect().left}))+"px"}),E.each({margin:"",padding:"",border:"Width"},function(i,o){E.cssHooks[i+o]={expand:function(e){for(var t=0,n={},r="string"==typeof e?e.split(" "):[e];t<4;t++)n[i+re[t]+o]=r[t]||r[t-2]||r[0];return n}},"margin"!==i&&(E.cssHooks[i+o].set=et)}),E.fn.extend({css:function(e,t){return z(this,function(e,t,n){var r,i,o={},a=0;if(Array.isArray(t)){for(r=We(e),i=t.length;a<i;a++)o[t[a]]=E.css(e,t[a],!1,r);return o}return void 0!==n?E.style(e,t,n):E.css(e,t)},e,t,1<arguments.length)}}),E.fn.delay=function(r,e){return r=E.fx&&E.fx.speeds[r]||r,e=e||"fx",this.queue(e,function(e,t){var n=g.setTimeout(e,r);t.stop=function(){g.clearTimeout(n)}})},Qe=v.createElement("input"),Ye=v.createElement("select").appendChild(v.createElement("option")),Qe.type="checkbox",b.checkOn=""!==Qe.value,b.optSelected=Ye.selected,(Qe=v.createElement("input")).value="t",Qe.type="radio",b.radioValue="t"===Qe.value;var rt,it=E.expr.attrHandle;E.fn.extend({attr:function(e,t){return z(this,E.attr,e,t,1<arguments.length)},removeAttr:function(e){return this.each(function(){E.removeAttr(this,e)})}}),E.extend({attr:function(e,t,n){var r,i,o=e.nodeType;if(3!==o&&8!==o&&2!==o)return"undefined"==typeof e.getAttribute?E.prop(e,t,n):(1===o&&E.isXMLDoc(e)||(i=E.attrHooks[t.toLowerCase()]||(E.expr.match.bool.test(t)?rt:void 0)),void 0!==n?null===n?void E.removeAttr(e,t):i&&"set"in i&&void 0!==(r=i.set(e,n,t))?r:(e.setAttribute(t,n+""),n):i&&"get"in i&&null!==(r=i.get(e,t))?r:null==(r=E.find.attr(e,t))?void 0:r)},attrHooks:{type:{set:function(e,t){if(!b.radioValue&&"radio"===t&&S(e,"input")){var n=e.value;return e.setAttribute("type",t),n&&(e.value=n),t}}}},removeAttr:function(e,t){var n,r=0,i=t&&t.match(I);if(i&&1===e.nodeType)while(n=i[r++])e.removeAttribute(n)}}),rt={set:function(e,t,n){return!1===t?E.removeAttr(e,n):e.setAttribute(n,n),n}},E.each(E.expr.match.bool.source.match(/\w+/g),function(e,t){var a=it[t]||E.find.attr;it[t]=function(e,t,n){var r,i,o=t.toLowerCase();return n||(i=it[o],it[o]=r,r=null!=a(e,t,n)?o:null,it[o]=i),r}});var ot=/^(?:input|select|textarea|button)$/i,at=/^(?:a|area)$/i;function st(e){return(e.match(I)||[]).join(" ")}function ut(e){return e.getAttribute&&e.getAttribute("class")||""}function lt(e){return Array.isArray(e)?e:"string"==typeof e&&e.match(I)||[]}E.fn.extend({prop:function(e,t){return z(this,E.prop,e,t,1<arguments.length)},removeProp:function(e){return this.each(function(){delete this[E.propFix[e]||e]})}}),E.extend({prop:function(e,t,n){var r,i,o=e.nodeType;if(3!==o&&8!==o&&2!==o)return 1===o&&E.isXMLDoc(e)||(t=E.propFix[t]||t,i=E.propHooks[t]),void 0!==n?i&&"set"in i&&void 0!==(r=i.set(e,n,t))?r:e[t]=n:i&&"get"in i&&null!==(r=i.get(e,t))?r:e[t]},propHooks:{tabIndex:{get:function(e){var t=E.find.attr(e,"tabindex");return t?parseInt(t,10):ot.test(e.nodeName)||at.test(e.nodeName)&&e.href?0:-1}}},propFix:{"for":"htmlFor","class":"className"}}),b.optSelected||(E.propHooks.selected={get:function(e){var t=e.parentNode;return t&&t.parentNode&&t.parentNode.selectedIndex,null},set:function(e){var t=e.parentNode;t&&(t.selectedIndex,t.parentNode&&t.parentNode.selectedIndex)}}),E.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){E.propFix[this.toLowerCase()]=this}),E.fn.extend({addClass:function(t){var e,n,r,i,o,a,s,u=0;if(x(t))return this.each(function(e){E(this).addClass(t.call(this,e,ut(this)))});if((e=lt(t)).length)while(n=this[u++])if(i=ut(n),r=1===n.nodeType&&" "+st(i)+" "){a=0;while(o=e[a++])r.indexOf(" "+o+" ")<0&&(r+=o+" ");i!==(s=st(r))&&n.setAttribute("class",s)}return this},removeClass:function(t){var e,n,r,i,o,a,s,u=0;if(x(t))return this.each(function(e){E(this).removeClass(t.call(this,e,ut(this)))});if(!arguments.length)return this.attr("class","");if((e=lt(t)).length)while(n=this[u++])if(i=ut(n),r=1===n.nodeType&&" "+st(i)+" "){a=0;while(o=e[a++])while(-1<r.indexOf(" "+o+" "))r=r.replace(" "+o+" "," ");i!==(s=st(r))&&n.setAttribute("class",s)}return this},toggleClass:function(i,t){var o=typeof i,a="string"===o||Array.isArray(i);return"boolean"==typeof t&&a?t?this.addClass(i):this.removeClass(i):x(i)?this.each(function(e){E(this).toggleClass(i.call(this,e,ut(this),t),t)}):this.each(function(){var e,t,n,r;if(a){t=0,n=E(this),r=lt(i);while(e=r[t++])n.hasClass(e)?n.removeClass(e):n.addClass(e)}else void 0!==i&&"boolean"!==o||((e=ut(this))&&G.set(this,"__className__",e),this.setAttribute&&this.setAttribute("class",e||!1===i?"":G.get(this,"__className__")||""))})},hasClass:function(e){var t,n,r=0;t=" "+e+" ";while(n=this[r++])if(1===n.nodeType&&-1<(" "+st(ut(n))+" ").indexOf(t))return!0;return!1}});var ct=/\r/g;E.fn.extend({val:function(n){var r,e,i,t=this[0];return arguments.length?(i=x(n),this.each(function(e){var t;1===this.nodeType&&(null==(t=i?n.call(this,e,E(this).val()):n)?t="":"number"==typeof t?t+="":Array.isArray(t)&&(t=E.map(t,function(e){return null==e?"":e+""})),(r=E.valHooks[this.type]||E.valHooks[this.nodeName.toLowerCase()])&&"set"in r&&void 0!==r.set(this,t,"value")||(this.value=t))})):t?(r=E.valHooks[t.type]||E.valHooks[t.nodeName.toLowerCase()])&&"get"in r&&void 0!==(e=r.get(t,"value"))?e:"string"==typeof(e=t.value)?e.replace(ct,""):null==e?"":e:void 0}}),E.extend({valHooks:{option:{get:function(e){var t=E.find.attr(e,"value");return null!=t?t:st(E.text(e))}},select:{get:function(e){var t,n,r,i=e.options,o=e.selectedIndex,a="select-one"===e.type,s=a?null:[],u=a?o+1:i.length;for(r=o<0?u:a?o:0;r<u;r++)if(((n=i[r]).selected||r===o)&&!n.disabled&&(!n.parentNode.disabled||!S(n.parentNode,"optgroup"))){if(t=E(n).val(),a)return t;s.push(t)}return s},set:function(e,t){var n,r,i=e.options,o=E.makeArray(t),a=i.length;while(a--)((r=i[a]).selected=-1<E.inArray(E.valHooks.option.get(r),o))&&(n=!0);return n||(e.selectedIndex=-1),o}}}}),E.each(["radio","checkbox"],function(){E.valHooks[this]={set:function(e,t){if(Array.isArray(t))return e.checked=-1<E.inArray(E(e).val(),t)}},b.checkOn||(E.valHooks[this].get=function(e){return null===e.getAttribute("value")?"on":e.value})}),b.focusin="onfocusin"in g;var ft=/^(?:focusinfocus|focusoutblur)$/,dt=function(e){e.stopPropagation()};E.extend(E.event,{trigger:function(e,t,n,r){var i,o,a,s,u,l,c,f,d=[n||v],p=m.call(e,"type")?e.type:e,h=m.call(e,"namespace")?e.namespace.split("."):[];if(o=f=a=n=n||v,3!==n.nodeType&&8!==n.nodeType&&!ft.test(p+E.event.triggered)&&(-1<p.indexOf(".")&&(p=(h=p.split(".")).shift(),h.sort()),u=p.indexOf(":")<0&&"on"+p,(e=e[E.expando]?e:new E.Event(p,"object"==typeof e&&e)).isTrigger=r?2:3,e.namespace=h.join("."),e.rnamespace=e.namespace?new RegExp("(^|\\.)"+h.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,e.result=void 0,e.target||(e.target=n),t=null==t?[e]:E.makeArray(t,[e]),c=E.event.special[p]||{},r||!c.trigger||!1!==c.trigger.apply(n,t))){if(!r&&!c.noBubble&&!w(n)){for(s=c.delegateType||p,ft.test(s+p)||(o=o.parentNode);o;o=o.parentNode)d.push(o),a=o;a===(n.ownerDocument||v)&&d.push(a.defaultView||a.parentWindow||g)}i=0;while((o=d[i++])&&!e.isPropagationStopped())f=o,e.type=1<i?s:c.bindType||p,(l=(G.get(o,"events")||{})[e.type]&&G.get(o,"handle"))&&l.apply(o,t),(l=u&&o[u])&&l.apply&&Q(o)&&(e.result=l.apply(o,t),!1===e.result&&e.preventDefault());return e.type=p,r||e.isDefaultPrevented()||c._default&&!1!==c._default.apply(d.pop(),t)||!Q(n)||u&&x(n[p])&&!w(n)&&((a=n[u])&&(n[u]=null),E.event.triggered=p,e.isPropagationStopped()&&f.addEventListener(p,dt),n[p](),e.isPropagationStopped()&&f.removeEventListener(p,dt),E.event.triggered=void 0,a&&(n[u]=a)),e.result}},simulate:function(e,t,n){var r=E.extend(new E.Event,n,{type:e,isSimulated:!0});E.event.trigger(r,null,t)}}),E.fn.extend({trigger:function(e,t){return this.each(function(){E.event.trigger(e,t,this)})},triggerHandler:function(e,t){var n=this[0];if(n)return E.event.trigger(e,t,n,!0)}}),b.focusin||E.each({focus:"focusin",blur:"focusout"},function(n,r){var i=function(e){E.event.simulate(r,e.target,E.event.fix(e))};E.event.special[r]={setup:function(){var e=this.ownerDocument||this,t=G.access(e,r);t||e.addEventListener(n,i,!0),G.access(e,r,(t||0)+1)},teardown:function(){var e=this.ownerDocument||this,t=G.access(e,r)-1;t?G.access(e,r,t):(e.removeEventListener(n,i,!0),G.remove(e,r))}}});var pt,ht=/\[\]$/,gt=/\r?\n/g,vt=/^(?:submit|button|image|reset|file)$/i,yt=/^(?:input|select|textarea|keygen)/i;function mt(n,e,r,i){var t;if(Array.isArray(e))E.each(e,function(e,t){r||ht.test(n)?i(n,t):mt(n+"["+("object"==typeof t&&null!=t?e:"")+"]",t,r,i)});else if(r||"object"!==T(e))i(n,e);else for(t in e)mt(n+"["+t+"]",e[t],r,i)}E.param=function(e,t){var n,r=[],i=function(e,t){var n=x(t)?t():t;r[r.length]=encodeURIComponent(e)+"="+encodeURIComponent(null==n?"":n)};if(null==e)return"";if(Array.isArray(e)||e.jquery&&!E.isPlainObject(e))E.each(e,function(){i(this.name,this.value)});else for(n in e)mt(n,e[n],t,i);return r.join("&")},E.fn.extend({serialize:function(){return E.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var e=E.prop(this,"elements");return e?E.makeArray(e):this}).filter(function(){var e=this.type;return this.name&&!E(this).is(":disabled")&&yt.test(this.nodeName)&&!vt.test(e)&&(this.checked||!fe.test(e))}).map(function(e,t){var n=E(this).val();return null==n?null:Array.isArray(n)?E.map(n,function(e){return{name:t.name,value:e.replace(gt,"\r\n")}}):{name:t.name,value:n.replace(gt,"\r\n")}}).get()}}),E.fn.extend({wrapAll:function(e){var t;return this[0]&&(x(e)&&(e=e.call(this[0])),t=E(e,this[0].ownerDocument).eq(0).clone(!0),this[0].parentNode&&t.insertBefore(this[0]),t.map(function(){var e=this;while(e.firstElementChild)e=e.firstElementChild;return e}).append(this)),this},wrapInner:function(n){return x(n)?this.each(function(e){E(this).wrapInner(n.call(this,e))}):this.each(function(){var e=E(this),t=e.contents();t.length?t.wrapAll(n):e.append(n)})},wrap:function(t){var n=x(t);return this.each(function(e){E(this).wrapAll(n?t.call(this,e):t)})},unwrap:function(e){return this.parent(e).not("body").each(function(){E(this).replaceWith(this.childNodes)}),this}}),E.expr.pseudos.hidden=function(e){return!E.expr.pseudos.visible(e)},E.expr.pseudos.visible=function(e){return!!(e.offsetWidth||e.offsetHeight||e.getClientRects().length)},b.createHTMLDocument=((pt=v.implementation.createHTMLDocument("").body).innerHTML="<form></form><form></form>",2===pt.childNodes.length),E.parseHTML=function(e,t,n){return"string"!=typeof e?[]:("boolean"==typeof t&&(n=t,t=!1),t||(b.createHTMLDocument?((r=(t=v.implementation.createHTMLDocument("")).createElement("base")).href=v.location.href,t.head.appendChild(r)):t=v),o=!n&&[],(i=D.exec(e))?[t.createElement(i[1])]:(i=xe([e],t,o),o&&o.length&&E(o).remove(),E.merge([],i.childNodes)));var r,i,o},E.offset={setOffset:function(e,t,n){var r,i,o,a,s,u,l=E.css(e,"position"),c=E(e),f={};"static"===l&&(e.style.position="relative"),s=c.offset(),o=E.css(e,"top"),u=E.css(e,"left"),("absolute"===l||"fixed"===l)&&-1<(o+u).indexOf("auto")?(a=(r=c.position()).top,i=r.left):(a=parseFloat(o)||0,i=parseFloat(u)||0),x(t)&&(t=t.call(e,n,E.extend({},s))),null!=t.top&&(f.top=t.top-s.top+a),null!=t.left&&(f.left=t.left-s.left+i),"using"in t?t.using.call(e,f):c.css(f)}},E.fn.extend({offset:function(t){if(arguments.length)return void 0===t?this:this.each(function(e){E.offset.setOffset(this,t,e)});var e,n,r=this[0];return r?r.getClientRects().length?(e=r.getBoundingClientRect(),n=r.ownerDocument.defaultView,{top:e.top+n.pageYOffset,left:e.left+n.pageXOffset}):{top:0,left:0}:void 0},position:function(){if(this[0]){var e,t,n,r=this[0],i={top:0,left:0};if("fixed"===E.css(r,"position"))t=r.getBoundingClientRect();else{t=this.offset(),n=r.ownerDocument,e=r.offsetParent||n.documentElement;while(e&&(e===n.body||e===n.documentElement)&&"static"===E.css(e,"position"))e=e.parentNode;e&&e!==r&&1===e.nodeType&&((i=E(e).offset()).top+=E.css(e,"borderTopWidth",!0),i.left+=E.css(e,"borderLeftWidth",!0))}return{top:t.top-i.top-E.css(r,"marginTop",!0),left:t.left-i.left-E.css(r,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){var e=this.offsetParent;while(e&&"static"===E.css(e,"position"))e=e.offsetParent;return e||ie})}}),E.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(t,i){var o="pageYOffset"===i;E.fn[t]=function(e){return z(this,function(e,t,n){var r;if(w(e)?r=e:9===e.nodeType&&(r=e.defaultView),void 0===n)return r?r[i]:e[t];r?r.scrollTo(o?r.pageXOffset:n,o?n:r.pageYOffset):e[t]=n},t,e,arguments.length)}}),E.each(["top","left"],function(e,n){E.cssHooks[n]=ze(b.pixelPosition,function(e,t){if(t)return t=Fe(e,n),Me.test(t)?E(e).position()[n]+"px":t})}),E.each({Height:"height",Width:"width"},function(a,s){E.each({padding:"inner"+a,content:s,"":"outer"+a},function(r,o){E.fn[o]=function(e,t){var n=arguments.length&&(r||"boolean"!=typeof e),i=r||(!0===e||!0===t?"margin":"border");return z(this,function(e,t,n){var r;return w(e)?0===o.indexOf("outer")?e["inner"+a]:e.document.documentElement["client"+a]:9===e.nodeType?(r=e.documentElement,Math.max(e.body["scroll"+a],r["scroll"+a],e.body["offset"+a],r["offset"+a],r["client"+a])):void 0===n?E.css(e,t,i):E.style(e,t,n,i)},s,n?e:void 0,n)}})}),E.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "),function(e,n){E.fn[n]=function(e,t){return 0<arguments.length?this.on(n,null,e,t):this.trigger(n)}}),E.fn.extend({hover:function(e,t){return this.mouseenter(e).mouseleave(t||e)}}),E.fn.extend({bind:function(e,t,n){return this.on(e,null,t,n)},unbind:function(e,t){return this.off(e,null,t)},delegate:function(e,t,n,r){return this.on(t,e,n,r)},undelegate:function(e,t,n){return 1===arguments.length?this.off(e,"**"):this.off(t,e||"**",n)}}),E.proxy=function(e,t){var n,r,i;if("string"==typeof t&&(n=e[t],t=e,e=n),x(e))return r=s.call(arguments,2),(i=function(){return e.apply(t||this,r.concat(s.call(arguments)))}).guid=e.guid=e.guid||E.guid++,i},E.holdReady=function(e){e?E.readyWait++:E.ready(!0)},E.isArray=Array.isArray,E.parseJSON=JSON.parse,E.nodeName=S,E.isFunction=x,E.isWindow=w,E.camelCase=X,E.type=T,E.now=Date.now,E.isNumeric=function(e){var t=E.type(e);return("number"===t||"string"===t)&&!isNaN(e-parseFloat(e))},"function"==typeof define&&define.amd&&define("jquery",[],function(){return E});var bt=g.jQuery,xt=g.$;return E.noConflict=function(e){return g.$===E&&(g.$=xt),e&&g.jQuery===E&&(g.jQuery=bt),E},e||(g.jQuery=g.$=E),E});
    (function(e,t){'object'==typeof exports&&'undefined'!=typeof module?module.exports=t():'function'==typeof define&&define.amd?define(t):e.Popper=t()})(this,function(){'use strict';function e(e){return e&&'[object Function]'==={}.toString.call(e)}function t(e,t){if(1!==e.nodeType)return[];var o=e.ownerDocument.defaultView,n=o.getComputedStyle(e,null);return t?n[t]:n}function o(e){return'HTML'===e.nodeName?e:e.parentNode||e.host}function n(e){if(!e)return document.body;switch(e.nodeName){case'HTML':case'BODY':return e.ownerDocument.body;case'#document':return e.body;}var i=t(e),r=i.overflow,p=i.overflowX,s=i.overflowY;return /(auto|scroll|overlay)/.test(r+s+p)?e:n(o(e))}function i(e){return e&&e.referenceNode?e.referenceNode:e}function r(e){return 11===e?re:10===e?pe:re||pe}function p(e){if(!e)return document.documentElement;for(var o=r(10)?document.body:null,n=e.offsetParent||null;n===o&&e.nextElementSibling;)n=(e=e.nextElementSibling).offsetParent;var i=n&&n.nodeName;return i&&'BODY'!==i&&'HTML'!==i?-1!==['TH','TD','TABLE'].indexOf(n.nodeName)&&'static'===t(n,'position')?p(n):n:e?e.ownerDocument.documentElement:document.documentElement}function s(e){var t=e.nodeName;return'BODY'!==t&&('HTML'===t||p(e.firstElementChild)===e)}function d(e){return null===e.parentNode?e:d(e.parentNode)}function a(e,t){if(!e||!e.nodeType||!t||!t.nodeType)return document.documentElement;var o=e.compareDocumentPosition(t)&Node.DOCUMENT_POSITION_FOLLOWING,n=o?e:t,i=o?t:e,r=document.createRange();r.setStart(n,0),r.setEnd(i,0);var l=r.commonAncestorContainer;if(e!==l&&t!==l||n.contains(i))return s(l)?l:p(l);var f=d(e);return f.host?a(f.host,t):a(e,d(t).host)}function l(e){var t=1<arguments.length&&void 0!==arguments[1]?arguments[1]:'top',o='top'===t?'scrollTop':'scrollLeft',n=e.nodeName;if('BODY'===n||'HTML'===n){var i=e.ownerDocument.documentElement,r=e.ownerDocument.scrollingElement||i;return r[o]}return e[o]}function f(e,t){var o=2<arguments.length&&void 0!==arguments[2]&&arguments[2],n=l(t,'top'),i=l(t,'left'),r=o?-1:1;return e.top+=n*r,e.bottom+=n*r,e.left+=i*r,e.right+=i*r,e}function m(e,t){var o='x'===t?'Left':'Top',n='Left'==o?'Right':'Bottom';return parseFloat(e['border'+o+'Width'],10)+parseFloat(e['border'+n+'Width'],10)}function h(e,t,o,n){return ee(t['offset'+e],t['scroll'+e],o['client'+e],o['offset'+e],o['scroll'+e],r(10)?parseInt(o['offset'+e])+parseInt(n['margin'+('Height'===e?'Top':'Left')])+parseInt(n['margin'+('Height'===e?'Bottom':'Right')]):0)}function c(e){var t=e.body,o=e.documentElement,n=r(10)&&getComputedStyle(o);return{height:h('Height',t,o,n),width:h('Width',t,o,n)}}function g(e){return le({},e,{right:e.left+e.width,bottom:e.top+e.height})}function u(e){var o={};try{if(r(10)){o=e.getBoundingClientRect();var n=l(e,'top'),i=l(e,'left');o.top+=n,o.left+=i,o.bottom+=n,o.right+=i}else o=e.getBoundingClientRect()}catch(t){}var p={left:o.left,top:o.top,width:o.right-o.left,height:o.bottom-o.top},s='HTML'===e.nodeName?c(e.ownerDocument):{},d=s.width||e.clientWidth||p.width,a=s.height||e.clientHeight||p.height,f=e.offsetWidth-d,h=e.offsetHeight-a;if(f||h){var u=t(e);f-=m(u,'x'),h-=m(u,'y'),p.width-=f,p.height-=h}return g(p)}function b(e,o){var i=2<arguments.length&&void 0!==arguments[2]&&arguments[2],p=r(10),s='HTML'===o.nodeName,d=u(e),a=u(o),l=n(e),m=t(o),h=parseFloat(m.borderTopWidth,10),c=parseFloat(m.borderLeftWidth,10);i&&s&&(a.top=ee(a.top,0),a.left=ee(a.left,0));var b=g({top:d.top-a.top-h,left:d.left-a.left-c,width:d.width,height:d.height});if(b.marginTop=0,b.marginLeft=0,!p&&s){var w=parseFloat(m.marginTop,10),y=parseFloat(m.marginLeft,10);b.top-=h-w,b.bottom-=h-w,b.left-=c-y,b.right-=c-y,b.marginTop=w,b.marginLeft=y}return(p&&!i?o.contains(l):o===l&&'BODY'!==l.nodeName)&&(b=f(b,o)),b}function w(e){var t=1<arguments.length&&void 0!==arguments[1]&&arguments[1],o=e.ownerDocument.documentElement,n=b(e,o),i=ee(o.clientWidth,window.innerWidth||0),r=ee(o.clientHeight,window.innerHeight||0),p=t?0:l(o),s=t?0:l(o,'left'),d={top:p-n.top+n.marginTop,left:s-n.left+n.marginLeft,width:i,height:r};return g(d)}function y(e){var n=e.nodeName;if('BODY'===n||'HTML'===n)return!1;if('fixed'===t(e,'position'))return!0;var i=o(e);return!!i&&y(i)}function E(e){if(!e||!e.parentElement||r())return document.documentElement;for(var o=e.parentElement;o&&'none'===t(o,'transform');)o=o.parentElement;return o||document.documentElement}function v(e,t,r,p){var s=4<arguments.length&&void 0!==arguments[4]&&arguments[4],d={top:0,left:0},l=s?E(e):a(e,i(t));if('viewport'===p)d=w(l,s);else{var f;'scrollParent'===p?(f=n(o(t)),'BODY'===f.nodeName&&(f=e.ownerDocument.documentElement)):'window'===p?f=e.ownerDocument.documentElement:f=p;var m=b(f,l,s);if('HTML'===f.nodeName&&!y(l)){var h=c(e.ownerDocument),g=h.height,u=h.width;d.top+=m.top-m.marginTop,d.bottom=g+m.top,d.left+=m.left-m.marginLeft,d.right=u+m.left}else d=m}r=r||0;var v='number'==typeof r;return d.left+=v?r:r.left||0,d.top+=v?r:r.top||0,d.right-=v?r:r.right||0,d.bottom-=v?r:r.bottom||0,d}function x(e){var t=e.width,o=e.height;return t*o}function O(e,t,o,n,i){var r=5<arguments.length&&void 0!==arguments[5]?arguments[5]:0;if(-1===e.indexOf('auto'))return e;var p=v(o,n,r,i),s={top:{width:p.width,height:t.top-p.top},right:{width:p.right-t.right,height:p.height},bottom:{width:p.width,height:p.bottom-t.bottom},left:{width:t.left-p.left,height:p.height}},d=Object.keys(s).map(function(e){return le({key:e},s[e],{area:x(s[e])})}).sort(function(e,t){return t.area-e.area}),a=d.filter(function(e){var t=e.width,n=e.height;return t>=o.clientWidth&&n>=o.clientHeight}),l=0<a.length?a[0].key:d[0].key,f=e.split('-')[1];return l+(f?'-'+f:'')}function L(e,t,o){var n=3<arguments.length&&void 0!==arguments[3]?arguments[3]:null,r=n?E(t):a(t,i(o));return b(o,r,n)}function S(e){var t=e.ownerDocument.defaultView,o=t.getComputedStyle(e),n=parseFloat(o.marginTop||0)+parseFloat(o.marginBottom||0),i=parseFloat(o.marginLeft||0)+parseFloat(o.marginRight||0),r={width:e.offsetWidth+i,height:e.offsetHeight+n};return r}function T(e){var t={left:'right',right:'left',bottom:'top',top:'bottom'};return e.replace(/left|right|bottom|top/g,function(e){return t[e]})}function C(e,t,o){o=o.split('-')[0];var n=S(e),i={width:n.width,height:n.height},r=-1!==['right','left'].indexOf(o),p=r?'top':'left',s=r?'left':'top',d=r?'height':'width',a=r?'width':'height';return i[p]=t[p]+t[d]/2-n[d]/2,i[s]=o===s?t[s]-n[a]:t[T(s)],i}function D(e,t){return Array.prototype.find?e.find(t):e.filter(t)[0]}function N(e,t,o){if(Array.prototype.findIndex)return e.findIndex(function(e){return e[t]===o});var n=D(e,function(e){return e[t]===o});return e.indexOf(n)}function P(t,o,n){var i=void 0===n?t:t.slice(0,N(t,'name',n));return i.forEach(function(t){t['function']&&console.warn('`modifier.function` is deprecated, use `modifier.fn`!');var n=t['function']||t.fn;t.enabled&&e(n)&&(o.offsets.popper=g(o.offsets.popper),o.offsets.reference=g(o.offsets.reference),o=n(o,t))}),o}function k(){if(!this.state.isDestroyed){var e={instance:this,styles:{},arrowStyles:{},attributes:{},flipped:!1,offsets:{}};e.offsets.reference=L(this.state,this.popper,this.reference,this.options.positionFixed),e.placement=O(this.options.placement,e.offsets.reference,this.popper,this.reference,this.options.modifiers.flip.boundariesElement,this.options.modifiers.flip.padding),e.originalPlacement=e.placement,e.positionFixed=this.options.positionFixed,e.offsets.popper=C(this.popper,e.offsets.reference,e.placement),e.offsets.popper.position=this.options.positionFixed?'fixed':'absolute',e=P(this.modifiers,e),this.state.isCreated?this.options.onUpdate(e):(this.state.isCreated=!0,this.options.onCreate(e))}}function W(e,t){return e.some(function(e){var o=e.name,n=e.enabled;return n&&o===t})}function B(e){for(var t=[!1,'ms','Webkit','Moz','O'],o=e.charAt(0).toUpperCase()+e.slice(1),n=0;n<t.length;n++){var i=t[n],r=i?''+i+o:e;if('undefined'!=typeof document.body.style[r])return r}return null}function H(){return this.state.isDestroyed=!0,W(this.modifiers,'applyStyle')&&(this.popper.removeAttribute('x-placement'),this.popper.style.position='',this.popper.style.top='',this.popper.style.left='',this.popper.style.right='',this.popper.style.bottom='',this.popper.style.willChange='',this.popper.style[B('transform')]=''),this.disableEventListeners(),this.options.removeOnDestroy&&this.popper.parentNode.removeChild(this.popper),this}function A(e){var t=e.ownerDocument;return t?t.defaultView:window}function M(e,t,o,i){var r='BODY'===e.nodeName,p=r?e.ownerDocument.defaultView:e;p.addEventListener(t,o,{passive:!0}),r||M(n(p.parentNode),t,o,i),i.push(p)}function F(e,t,o,i){o.updateBound=i,A(e).addEventListener('resize',o.updateBound,{passive:!0});var r=n(e);return M(r,'scroll',o.updateBound,o.scrollParents),o.scrollElement=r,o.eventsEnabled=!0,o}function I(){this.state.eventsEnabled||(this.state=F(this.reference,this.options,this.state,this.scheduleUpdate))}function R(e,t){return A(e).removeEventListener('resize',t.updateBound),t.scrollParents.forEach(function(e){e.removeEventListener('scroll',t.updateBound)}),t.updateBound=null,t.scrollParents=[],t.scrollElement=null,t.eventsEnabled=!1,t}function U(){this.state.eventsEnabled&&(cancelAnimationFrame(this.scheduleUpdate),this.state=R(this.reference,this.state))}function Y(e){return''!==e&&!isNaN(parseFloat(e))&&isFinite(e)}function V(e,t){Object.keys(t).forEach(function(o){var n='';-1!==['width','height','top','right','bottom','left'].indexOf(o)&&Y(t[o])&&(n='px'),e.style[o]=t[o]+n})}function j(e,t){Object.keys(t).forEach(function(o){var n=t[o];!1===n?e.removeAttribute(o):e.setAttribute(o,t[o])})}function q(e,t){var o=e.offsets,n=o.popper,i=o.reference,r=$,p=function(e){return e},s=r(i.width),d=r(n.width),a=-1!==['left','right'].indexOf(e.placement),l=-1!==e.placement.indexOf('-'),f=t?a||l||s%2==d%2?r:Z:p,m=t?r:p;return{left:f(1==s%2&&1==d%2&&!l&&t?n.left-1:n.left),top:m(n.top),bottom:m(n.bottom),right:f(n.right)}}function K(e,t,o){var n=D(e,function(e){var o=e.name;return o===t}),i=!!n&&e.some(function(e){return e.name===o&&e.enabled&&e.order<n.order});if(!i){var r='`'+t+'`';console.warn('`'+o+'`'+' modifier is required by '+r+' modifier in order to work, be sure to include it before '+r+'!')}return i}function z(e){return'end'===e?'start':'start'===e?'end':e}function G(e){var t=1<arguments.length&&void 0!==arguments[1]&&arguments[1],o=he.indexOf(e),n=he.slice(o+1).concat(he.slice(0,o));return t?n.reverse():n}function _(e,t,o,n){var i=e.match(/((?:\-|\+)?\d*\.?\d*)(.*)/),r=+i[1],p=i[2];if(!r)return e;if(0===p.indexOf('%')){var s;switch(p){case'%p':s=o;break;case'%':case'%r':default:s=n;}var d=g(s);return d[t]/100*r}if('vh'===p||'vw'===p){var a;return a='vh'===p?ee(document.documentElement.clientHeight,window.innerHeight||0):ee(document.documentElement.clientWidth,window.innerWidth||0),a/100*r}return r}function X(e,t,o,n){var i=[0,0],r=-1!==['right','left'].indexOf(n),p=e.split(/(\+|\-)/).map(function(e){return e.trim()}),s=p.indexOf(D(p,function(e){return-1!==e.search(/,|\s/)}));p[s]&&-1===p[s].indexOf(',')&&console.warn('Offsets separated by white space(s) are deprecated, use a comma (,) instead.');var d=/\s*,\s*|\s+/,a=-1===s?[p]:[p.slice(0,s).concat([p[s].split(d)[0]]),[p[s].split(d)[1]].concat(p.slice(s+1))];return a=a.map(function(e,n){var i=(1===n?!r:r)?'height':'width',p=!1;return e.reduce(function(e,t){return''===e[e.length-1]&&-1!==['+','-'].indexOf(t)?(e[e.length-1]=t,p=!0,e):p?(e[e.length-1]+=t,p=!1,e):e.concat(t)},[]).map(function(e){return _(e,i,t,o)})}),a.forEach(function(e,t){e.forEach(function(o,n){Y(o)&&(i[t]+=o*('-'===e[n-1]?-1:1))})}),i}function J(e,t){var o,n=t.offset,i=e.placement,r=e.offsets,p=r.popper,s=r.reference,d=i.split('-')[0];return o=Y(+n)?[+n,0]:X(n,p,s,d),'left'===d?(p.top+=o[0],p.left-=o[1]):'right'===d?(p.top+=o[0],p.left+=o[1]):'top'===d?(p.left+=o[0],p.top-=o[1]):'bottom'===d&&(p.left+=o[0],p.top+=o[1]),e.popper=p,e}var Q=Math.min,Z=Math.floor,$=Math.round,ee=Math.max,te='undefined'!=typeof window&&'undefined'!=typeof document&&'undefined'!=typeof navigator,oe=function(){for(var e=['Edge','Trident','Firefox'],t=0;t<e.length;t+=1)if(te&&0<=navigator.userAgent.indexOf(e[t]))return 1;return 0}(),ne=te&&window.Promise,ie=ne?function(e){var t=!1;return function(){t||(t=!0,window.Promise.resolve().then(function(){t=!1,e()}))}}:function(e){var t=!1;return function(){t||(t=!0,setTimeout(function(){t=!1,e()},oe))}},re=te&&!!(window.MSInputMethodContext&&document.documentMode),pe=te&&/MSIE 10/.test(navigator.userAgent),se=function(e,t){if(!(e instanceof t))throw new TypeError('Cannot call a class as a function')},de=function(){function e(e,t){for(var o,n=0;n<t.length;n++)o=t[n],o.enumerable=o.enumerable||!1,o.configurable=!0,'value'in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}return function(t,o,n){return o&&e(t.prototype,o),n&&e(t,n),t}}(),ae=function(e,t,o){return t in e?Object.defineProperty(e,t,{value:o,enumerable:!0,configurable:!0,writable:!0}):e[t]=o,e},le=Object.assign||function(e){for(var t,o=1;o<arguments.length;o++)for(var n in t=arguments[o],t)Object.prototype.hasOwnProperty.call(t,n)&&(e[n]=t[n]);return e},fe=te&&/Firefox/i.test(navigator.userAgent),me=['auto-start','auto','auto-end','top-start','top','top-end','right-start','right','right-end','bottom-end','bottom','bottom-start','left-end','left','left-start'],he=me.slice(3),ce={FLIP:'flip',CLOCKWISE:'clockwise',COUNTERCLOCKWISE:'counterclockwise'},ge=function(){function t(o,n){var i=this,r=2<arguments.length&&void 0!==arguments[2]?arguments[2]:{};se(this,t),this.scheduleUpdate=function(){return requestAnimationFrame(i.update)},this.update=ie(this.update.bind(this)),this.options=le({},t.Defaults,r),this.state={isDestroyed:!1,isCreated:!1,scrollParents:[]},this.reference=o&&o.jquery?o[0]:o,this.popper=n&&n.jquery?n[0]:n,this.options.modifiers={},Object.keys(le({},t.Defaults.modifiers,r.modifiers)).forEach(function(e){i.options.modifiers[e]=le({},t.Defaults.modifiers[e]||{},r.modifiers?r.modifiers[e]:{})}),this.modifiers=Object.keys(this.options.modifiers).map(function(e){return le({name:e},i.options.modifiers[e])}).sort(function(e,t){return e.order-t.order}),this.modifiers.forEach(function(t){t.enabled&&e(t.onLoad)&&t.onLoad(i.reference,i.popper,i.options,t,i.state)}),this.update();var p=this.options.eventsEnabled;p&&this.enableEventListeners(),this.state.eventsEnabled=p}return de(t,[{key:'update',value:function(){return k.call(this)}},{key:'destroy',value:function(){return H.call(this)}},{key:'enableEventListeners',value:function(){return I.call(this)}},{key:'disableEventListeners',value:function(){return U.call(this)}}]),t}();return ge.Utils=('undefined'==typeof window?global:window).PopperUtils,ge.placements=me,ge.Defaults={placement:'bottom',positionFixed:!1,eventsEnabled:!0,removeOnDestroy:!1,onCreate:function(){},onUpdate:function(){},modifiers:{shift:{order:100,enabled:!0,fn:function(e){var t=e.placement,o=t.split('-')[0],n=t.split('-')[1];if(n){var i=e.offsets,r=i.reference,p=i.popper,s=-1!==['bottom','top'].indexOf(o),d=s?'left':'top',a=s?'width':'height',l={start:ae({},d,r[d]),end:ae({},d,r[d]+r[a]-p[a])};e.offsets.popper=le({},p,l[n])}return e}},offset:{order:200,enabled:!0,fn:J,offset:0},preventOverflow:{order:300,enabled:!0,fn:function(e,t){var o=t.boundariesElement||p(e.instance.popper);e.instance.reference===o&&(o=p(o));var n=B('transform'),i=e.instance.popper.style,r=i.top,s=i.left,d=i[n];i.top='',i.left='',i[n]='';var a=v(e.instance.popper,e.instance.reference,t.padding,o,e.positionFixed);i.top=r,i.left=s,i[n]=d,t.boundaries=a;var l=t.priority,f=e.offsets.popper,m={primary:function(e){var o=f[e];return f[e]<a[e]&&!t.escapeWithReference&&(o=ee(f[e],a[e])),ae({},e,o)},secondary:function(e){var o='right'===e?'left':'top',n=f[o];return f[e]>a[e]&&!t.escapeWithReference&&(n=Q(f[o],a[e]-('right'===e?f.width:f.height))),ae({},o,n)}};return l.forEach(function(e){var t=-1===['left','top'].indexOf(e)?'secondary':'primary';f=le({},f,m[t](e))}),e.offsets.popper=f,e},priority:['left','right','top','bottom'],padding:5,boundariesElement:'scrollParent'},keepTogether:{order:400,enabled:!0,fn:function(e){var t=e.offsets,o=t.popper,n=t.reference,i=e.placement.split('-')[0],r=Z,p=-1!==['top','bottom'].indexOf(i),s=p?'right':'bottom',d=p?'left':'top',a=p?'width':'height';return o[s]<r(n[d])&&(e.offsets.popper[d]=r(n[d])-o[a]),o[d]>r(n[s])&&(e.offsets.popper[d]=r(n[s])),e}},arrow:{order:500,enabled:!0,fn:function(e,o){var n;if(!K(e.instance.modifiers,'arrow','keepTogether'))return e;var i=o.element;if('string'==typeof i){if(i=e.instance.popper.querySelector(i),!i)return e;}else if(!e.instance.popper.contains(i))return console.warn('WARNING: `arrow.element` must be child of its popper element!'),e;var r=e.placement.split('-')[0],p=e.offsets,s=p.popper,d=p.reference,a=-1!==['left','right'].indexOf(r),l=a?'height':'width',f=a?'Top':'Left',m=f.toLowerCase(),h=a?'left':'top',c=a?'bottom':'right',u=S(i)[l];d[c]-u<s[m]&&(e.offsets.popper[m]-=s[m]-(d[c]-u)),d[m]+u>s[c]&&(e.offsets.popper[m]+=d[m]+u-s[c]),e.offsets.popper=g(e.offsets.popper);var b=d[m]+d[l]/2-u/2,w=t(e.instance.popper),y=parseFloat(w['margin'+f],10),E=parseFloat(w['border'+f+'Width'],10),v=b-e.offsets.popper[m]-y-E;return v=ee(Q(s[l]-u,v),0),e.arrowElement=i,e.offsets.arrow=(n={},ae(n,m,$(v)),ae(n,h,''),n),e},element:'[x-arrow]'},flip:{order:600,enabled:!0,fn:function(e,t){if(W(e.instance.modifiers,'inner'))return e;if(e.flipped&&e.placement===e.originalPlacement)return e;var o=v(e.instance.popper,e.instance.reference,t.padding,t.boundariesElement,e.positionFixed),n=e.placement.split('-')[0],i=T(n),r=e.placement.split('-')[1]||'',p=[];switch(t.behavior){case ce.FLIP:p=[n,i];break;case ce.CLOCKWISE:p=G(n);break;case ce.COUNTERCLOCKWISE:p=G(n,!0);break;default:p=t.behavior;}return p.forEach(function(s,d){if(n!==s||p.length===d+1)return e;n=e.placement.split('-')[0],i=T(n);var a=e.offsets.popper,l=e.offsets.reference,f=Z,m='left'===n&&f(a.right)>f(l.left)||'right'===n&&f(a.left)<f(l.right)||'top'===n&&f(a.bottom)>f(l.top)||'bottom'===n&&f(a.top)<f(l.bottom),h=f(a.left)<f(o.left),c=f(a.right)>f(o.right),g=f(a.top)<f(o.top),u=f(a.bottom)>f(o.bottom),b='left'===n&&h||'right'===n&&c||'top'===n&&g||'bottom'===n&&u,w=-1!==['top','bottom'].indexOf(n),y=!!t.flipVariations&&(w&&'start'===r&&h||w&&'end'===r&&c||!w&&'start'===r&&g||!w&&'end'===r&&u),E=!!t.flipVariationsByContent&&(w&&'start'===r&&c||w&&'end'===r&&h||!w&&'start'===r&&u||!w&&'end'===r&&g),v=y||E;(m||b||v)&&(e.flipped=!0,(m||b)&&(n=p[d+1]),v&&(r=z(r)),e.placement=n+(r?'-'+r:''),e.offsets.popper=le({},e.offsets.popper,C(e.instance.popper,e.offsets.reference,e.placement)),e=P(e.instance.modifiers,e,'flip'))}),e},behavior:'flip',padding:5,boundariesElement:'viewport',flipVariations:!1,flipVariationsByContent:!1},inner:{order:700,enabled:!1,fn:function(e){var t=e.placement,o=t.split('-')[0],n=e.offsets,i=n.popper,r=n.reference,p=-1!==['left','right'].indexOf(o),s=-1===['top','left'].indexOf(o);return i[p?'left':'top']=r[o]-(s?i[p?'width':'height']:0),e.placement=T(t),e.offsets.popper=g(i),e}},hide:{order:800,enabled:!0,fn:function(e){if(!K(e.instance.modifiers,'hide','preventOverflow'))return e;var t=e.offsets.reference,o=D(e.instance.modifiers,function(e){return'preventOverflow'===e.name}).boundaries;if(t.bottom<o.top||t.left>o.right||t.top>o.bottom||t.right<o.left){if(!0===e.hide)return e;e.hide=!0,e.attributes['x-out-of-boundaries']=''}else{if(!1===e.hide)return e;e.hide=!1,e.attributes['x-out-of-boundaries']=!1}return e}},computeStyle:{order:850,enabled:!0,fn:function(e,t){var o=t.x,n=t.y,i=e.offsets.popper,r=D(e.instance.modifiers,function(e){return'applyStyle'===e.name}).gpuAcceleration;void 0!==r&&console.warn('WARNING: `gpuAcceleration` option moved to `computeStyle` modifier and will not be supported in future versions of Popper.js!');var s,d,a=void 0===r?t.gpuAcceleration:r,l=p(e.instance.popper),f=u(l),m={position:i.position},h=q(e,2>window.devicePixelRatio||!fe),c='bottom'===o?'top':'bottom',g='right'===n?'left':'right',b=B('transform');if(d='bottom'==c?'HTML'===l.nodeName?-l.clientHeight+h.bottom:-f.height+h.bottom:h.top,s='right'==g?'HTML'===l.nodeName?-l.clientWidth+h.right:-f.width+h.right:h.left,a&&b)m[b]='translate3d('+s+'px, '+d+'px, 0)',m[c]=0,m[g]=0,m.willChange='transform';else{var w='bottom'==c?-1:1,y='right'==g?-1:1;m[c]=d*w,m[g]=s*y,m.willChange=c+', '+g}var E={"x-placement":e.placement};return e.attributes=le({},E,e.attributes),e.styles=le({},m,e.styles),e.arrowStyles=le({},e.offsets.arrow,e.arrowStyles),e},gpuAcceleration:!0,x:'bottom',y:'right'},applyStyle:{order:900,enabled:!0,fn:function(e){return V(e.instance.popper,e.styles),j(e.instance.popper,e.attributes),e.arrowElement&&Object.keys(e.arrowStyles).length&&V(e.arrowElement,e.arrowStyles),e},onLoad:function(e,t,o,n,i){var r=L(i,t,e,o.positionFixed),p=O(o.placement,r,t,e,o.modifiers.flip.boundariesElement,o.modifiers.flip.padding);return t.setAttribute('x-placement',p),V(t,{position:o.positionFixed?'fixed':'absolute'}),o},gpuAcceleration:void 0}}},ge});

    (function (global, factory) {
        typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports, require('jquery'), require('popper.js')) :
            typeof define === 'function' && define.amd ? define(['exports', 'jquery', 'popper.js'], factory) :
                (global = global || self, factory(global.bootstrap = {}, global.jQuery, global.Popper));
    }(this, (function (exports, $, Popper) { 'use strict';

        $ = $ && $.hasOwnProperty('default') ? $['default'] : $;
        Popper = Popper && Popper.hasOwnProperty('default') ? Popper['default'] : Popper;

        function _defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }

        function _createClass(Constructor, protoProps, staticProps) {
            if (protoProps) _defineProperties(Constructor.prototype, protoProps);
            if (staticProps) _defineProperties(Constructor, staticProps);
            return Constructor;
        }

        function _defineProperty(obj, key, value) {
            if (key in obj) {
                Object.defineProperty(obj, key, {
                    value: value,
                    enumerable: true,
                    configurable: true,
                    writable: true
                });
            } else {
                obj[key] = value;
            }

            return obj;
        }

        function ownKeys(object, enumerableOnly) {
            var keys = Object.keys(object);

            if (Object.getOwnPropertySymbols) {
                var symbols = Object.getOwnPropertySymbols(object);
                if (enumerableOnly) symbols = symbols.filter(function (sym) {
                    return Object.getOwnPropertyDescriptor(object, sym).enumerable;
                });
                keys.push.apply(keys, symbols);
            }

            return keys;
        }

        function _objectSpread2(target) {
            for (var i = 1; i < arguments.length; i++) {
                var source = arguments[i] != null ? arguments[i] : {};

                if (i % 2) {
                    ownKeys(Object(source), true).forEach(function (key) {
                        _defineProperty(target, key, source[key]);
                    });
                } else if (Object.getOwnPropertyDescriptors) {
                    Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
                } else {
                    ownKeys(Object(source)).forEach(function (key) {
                        Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
                    });
                }
            }

            return target;
        }

        function _inheritsLoose(subClass, superClass) {
            subClass.prototype = Object.create(superClass.prototype);
            subClass.prototype.constructor = subClass;
            subClass.__proto__ = superClass;
        }

        /**
         * --------------------------------------------------------------------------
         * Bootstrap (v4.4.1): util.js
         * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
         * --------------------------------------------------------------------------
         */
        /**
         * ------------------------------------------------------------------------
         * Private TransitionEnd Helpers
         * ------------------------------------------------------------------------
         */

        var TRANSITION_END = 'transitionend';
        var MAX_UID = 1000000;
        var MILLISECONDS_MULTIPLIER = 1000; // Shoutout AngusCroll (https://goo.gl/pxwQGp)

        function toType(obj) {
            return {}.toString.call(obj).match(/\s([a-z]+)/i)[1].toLowerCase();
        }

        function getSpecialTransitionEndEvent() {
            return {
                bindType: TRANSITION_END,
                delegateType: TRANSITION_END,
                handle: function handle(event) {
                    if ($(event.target).is(this)) {
                        return event.handleObj.handler.apply(this, arguments); // eslint-disable-line prefer-rest-params
                    }

                    return undefined; // eslint-disable-line no-undefined
                }
            };
        }

        function transitionEndEmulator(duration) {
            var _this = this;

            var called = false;
            $(this).one(Util.TRANSITION_END, function () {
                called = true;
            });
            setTimeout(function () {
                if (!called) {
                    Util.triggerTransitionEnd(_this);
                }
            }, duration);
            return this;
        }

        function setTransitionEndSupport() {
            $.fn.emulateTransitionEnd = transitionEndEmulator;
            $.event.special[Util.TRANSITION_END] = getSpecialTransitionEndEvent();
        }
        /**
         * --------------------------------------------------------------------------
         * Public Util Api
         * --------------------------------------------------------------------------
         */


        var Util = {
            TRANSITION_END: 'bsTransitionEnd',
            getUID: function getUID(prefix) {
                do {
                    // eslint-disable-next-line no-bitwise
                    prefix += ~~(Math.random() * MAX_UID); // "~~" acts like a faster Math.floor() here
                } while (document.getElementById(prefix));

                return prefix;
            },
            getSelectorFromElement: function getSelectorFromElement(element) {
                var selector = element.getAttribute('data-target');

                if (!selector || selector === '#') {
                    var hrefAttr = element.getAttribute('href');
                    selector = hrefAttr && hrefAttr !== '#' ? hrefAttr.trim() : '';
                }

                try {
                    return document.querySelector(selector) ? selector : null;
                } catch (err) {
                    return null;
                }
            },
            getTransitionDurationFromElement: function getTransitionDurationFromElement(element) {
                if (!element) {
                    return 0;
                } // Get transition-duration of the element


                var transitionDuration = $(element).css('transition-duration');
                var transitionDelay = $(element).css('transition-delay');
                var floatTransitionDuration = parseFloat(transitionDuration);
                var floatTransitionDelay = parseFloat(transitionDelay); // Return 0 if element or transition duration is not found

                if (!floatTransitionDuration && !floatTransitionDelay) {
                    return 0;
                } // If multiple durations are defined, take the first


                transitionDuration = transitionDuration.split(',')[0];
                transitionDelay = transitionDelay.split(',')[0];
                return (parseFloat(transitionDuration) + parseFloat(transitionDelay)) * MILLISECONDS_MULTIPLIER;
            },
            reflow: function reflow(element) {
                return element.offsetHeight;
            },
            triggerTransitionEnd: function triggerTransitionEnd(element) {
                $(element).trigger(TRANSITION_END);
            },
            // TODO: Remove in v5
            supportsTransitionEnd: function supportsTransitionEnd() {
                return Boolean(TRANSITION_END);
            },
            isElement: function isElement(obj) {
                return (obj[0] || obj).nodeType;
            },
            typeCheckConfig: function typeCheckConfig(componentName, config, configTypes) {
                for (var property in configTypes) {
                    if (Object.prototype.hasOwnProperty.call(configTypes, property)) {
                        var expectedTypes = configTypes[property];
                        var value = config[property];
                        var valueType = value && Util.isElement(value) ? 'element' : toType(value);

                        if (!new RegExp(expectedTypes).test(valueType)) {
                            throw new Error(componentName.toUpperCase() + ": " + ("Option \"" + property + "\" provided type \"" + valueType + "\" ") + ("but expected type \"" + expectedTypes + "\"."));
                        }
                    }
                }
            },
            findShadowRoot: function findShadowRoot(element) {
                if (!document.documentElement.attachShadow) {
                    return null;
                } // Can find the shadow root otherwise it'll return the document


                if (typeof element.getRootNode === 'function') {
                    var root = element.getRootNode();
                    return root instanceof ShadowRoot ? root : null;
                }

                if (element instanceof ShadowRoot) {
                    return element;
                } // when we don't find a shadow root


                if (!element.parentNode) {
                    return null;
                }

                return Util.findShadowRoot(element.parentNode);
            },
            jQueryDetection: function jQueryDetection() {
                if (typeof $ === 'undefined') {
                    throw new TypeError('Bootstrap\'s JavaScript requires jQuery. jQuery must be included before Bootstrap\'s JavaScript.');
                }

                var version = $.fn.jquery.split(' ')[0].split('.');
                var minMajor = 1;
                var ltMajor = 2;
                var minMinor = 9;
                var minPatch = 1;
                var maxMajor = 4;

                if (version[0] < ltMajor && version[1] < minMinor || version[0] === minMajor && version[1] === minMinor && version[2] < minPatch || version[0] >= maxMajor) {
                    throw new Error('Bootstrap\'s JavaScript requires at least jQuery v1.9.1 but less than v4.0.0');
                }
            }
        };
        Util.jQueryDetection();
        setTransitionEndSupport();

        /**
         * ------------------------------------------------------------------------
         * Constants
         * ------------------------------------------------------------------------
         */

        var NAME = 'alert';
        var VERSION = '4.4.1';
        var DATA_KEY = 'bs.alert';
        var EVENT_KEY = "." + DATA_KEY;
        var DATA_API_KEY = '.data-api';
        var JQUERY_NO_CONFLICT = $.fn[NAME];
        var Selector = {
            DISMISS: '[data-dismiss="alert"]'
        };
        var Event = {
            CLOSE: "close" + EVENT_KEY,
            CLOSED: "closed" + EVENT_KEY,
            CLICK_DATA_API: "click" + EVENT_KEY + DATA_API_KEY
        };
        var ClassName = {
            ALERT: 'alert',
            FADE: 'fade',
            SHOW: 'show'
        };
        /**
         * ------------------------------------------------------------------------
         * Class Definition
         * ------------------------------------------------------------------------
         */

        var Alert =
            /*#__PURE__*/
            function () {
                function Alert(element) {
                    this._element = element;
                } // Getters


                var _proto = Alert.prototype;

                // Public
                _proto.close = function close(element) {
                    var rootElement = this._element;

                    if (element) {
                        rootElement = this._getRootElement(element);
                    }

                    var customEvent = this._triggerCloseEvent(rootElement);

                    if (customEvent.isDefaultPrevented()) {
                        return;
                    }

                    this._removeElement(rootElement);
                };

                _proto.dispose = function dispose() {
                    $.removeData(this._element, DATA_KEY);
                    this._element = null;
                } // Private
                ;

                _proto._getRootElement = function _getRootElement(element) {
                    var selector = Util.getSelectorFromElement(element);
                    var parent = false;

                    if (selector) {
                        parent = document.querySelector(selector);
                    }

                    if (!parent) {
                        parent = $(element).closest("." + ClassName.ALERT)[0];
                    }

                    return parent;
                };

                _proto._triggerCloseEvent = function _triggerCloseEvent(element) {
                    var closeEvent = $.Event(Event.CLOSE);
                    $(element).trigger(closeEvent);
                    return closeEvent;
                };

                _proto._removeElement = function _removeElement(element) {
                    var _this = this;

                    $(element).removeClass(ClassName.SHOW);

                    if (!$(element).hasClass(ClassName.FADE)) {
                        this._destroyElement(element);

                        return;
                    }

                    var transitionDuration = Util.getTransitionDurationFromElement(element);
                    $(element).one(Util.TRANSITION_END, function (event) {
                        return _this._destroyElement(element, event);
                    }).emulateTransitionEnd(transitionDuration);
                };

                _proto._destroyElement = function _destroyElement(element) {
                    $(element).detach().trigger(Event.CLOSED).remove();
                } // Static
                ;

                Alert._jQueryInterface = function _jQueryInterface(config) {
                    return this.each(function () {
                        var $element = $(this);
                        var data = $element.data(DATA_KEY);

                        if (!data) {
                            data = new Alert(this);
                            $element.data(DATA_KEY, data);
                        }

                        if (config === 'close') {
                            data[config](this);
                        }
                    });
                };

                Alert._handleDismiss = function _handleDismiss(alertInstance) {
                    return function (event) {
                        if (event) {
                            event.preventDefault();
                        }

                        alertInstance.close(this);
                    };
                };

                _createClass(Alert, null, [{
                    key: "VERSION",
                    get: function get() {
                        return VERSION;
                    }
                }]);

                return Alert;
            }();
        /**
         * ------------------------------------------------------------------------
         * Data Api implementation
         * ------------------------------------------------------------------------
         */


        $(document).on(Event.CLICK_DATA_API, Selector.DISMISS, Alert._handleDismiss(new Alert()));
        /**
         * ------------------------------------------------------------------------
         * jQuery
         * ------------------------------------------------------------------------
         */

        $.fn[NAME] = Alert._jQueryInterface;
        $.fn[NAME].Constructor = Alert;

        $.fn[NAME].noConflict = function () {
            $.fn[NAME] = JQUERY_NO_CONFLICT;
            return Alert._jQueryInterface;
        };

        /**
         * ------------------------------------------------------------------------
         * Constants
         * ------------------------------------------------------------------------
         */

        var NAME$1 = 'button';
        var VERSION$1 = '4.4.1';
        var DATA_KEY$1 = 'bs.button';
        var EVENT_KEY$1 = "." + DATA_KEY$1;
        var DATA_API_KEY$1 = '.data-api';
        var JQUERY_NO_CONFLICT$1 = $.fn[NAME$1];
        var ClassName$1 = {
            ACTIVE: 'active',
            BUTTON: 'nm_btn',
            FOCUS: 'focus'
        };
        var Selector$1 = {
            DATA_TOGGLE_CARROT: '[data-toggle^="button"]',
            DATA_TOGGLES: '[data-toggle="buttons"]',
            DATA_TOGGLE: '[data-toggle="button"]',
            DATA_TOGGLES_BUTTONS: '[data-toggle="buttons"] .nm_btn',
            INPUT: 'input:not([type="hidden"])',
            ACTIVE: '.active',
            BUTTON: '.nm_btn'
        };
        var Event$1 = {
            CLICK_DATA_API: "click" + EVENT_KEY$1 + DATA_API_KEY$1,
            FOCUS_BLUR_DATA_API: "focus" + EVENT_KEY$1 + DATA_API_KEY$1 + " " + ("blur" + EVENT_KEY$1 + DATA_API_KEY$1),
            LOAD_DATA_API: "load" + EVENT_KEY$1 + DATA_API_KEY$1
        };
        /**
         * ------------------------------------------------------------------------
         * Class Definition
         * ------------------------------------------------------------------------
         */

        var Button =
            /*#__PURE__*/
            function () {
                function Button(element) {
                    this._element = element;
                } // Getters


                var _proto = Button.prototype;

                // Public
                _proto.toggle = function toggle() {
                    var triggerChangeEvent = true;
                    var addAriaPressed = true;
                    var rootElement = $(this._element).closest(Selector$1.DATA_TOGGLES)[0];

                    if (rootElement) {
                        var input = this._element.querySelector(Selector$1.INPUT);

                        if (input) {
                            if (input.type === 'radio') {
                                if (input.checked && this._element.classList.contains(ClassName$1.ACTIVE)) {
                                    triggerChangeEvent = false;
                                } else {
                                    var activeElement = rootElement.querySelector(Selector$1.ACTIVE);

                                    if (activeElement) {
                                        $(activeElement).removeClass(ClassName$1.ACTIVE);
                                    }
                                }
                            } else if (input.type === 'checkbox') {
                                if (this._element.tagName === 'LABEL' && input.checked === this._element.classList.contains(ClassName$1.ACTIVE)) {
                                    triggerChangeEvent = false;
                                }
                            } else {
                                // if it's not a radio button or checkbox don't add a pointless/invalid checked property to the input
                                triggerChangeEvent = false;
                            }

                            if (triggerChangeEvent) {
                                input.checked = !this._element.classList.contains(ClassName$1.ACTIVE);
                                $(input).trigger('change');
                            }

                            input.focus();
                            addAriaPressed = false;
                        }
                    }

                    if (!(this._element.hasAttribute('disabled') || this._element.classList.contains('disabled'))) {
                        if (addAriaPressed) {
                            this._element.setAttribute('aria-pressed', !this._element.classList.contains(ClassName$1.ACTIVE));
                        }

                        if (triggerChangeEvent) {
                            $(this._element).toggleClass(ClassName$1.ACTIVE);
                        }
                    }
                };

                _proto.dispose = function dispose() {
                    $.removeData(this._element, DATA_KEY$1);
                    this._element = null;
                } // Static
                ;

                Button._jQueryInterface = function _jQueryInterface(config) {
                    return this.each(function () {
                        var data = $(this).data(DATA_KEY$1);

                        if (!data) {
                            data = new Button(this);
                            $(this).data(DATA_KEY$1, data);
                        }

                        if (config === 'toggle') {
                            data[config]();
                        }
                    });
                };

                _createClass(Button, null, [{
                    key: "VERSION",
                    get: function get() {
                        return VERSION$1;
                    }
                }]);

                return Button;
            }();
        /**
         * ------------------------------------------------------------------------
         * Data Api implementation
         * ------------------------------------------------------------------------
         */


        $(document).on(Event$1.CLICK_DATA_API, Selector$1.DATA_TOGGLE_CARROT, function (event) {
            var button = event.target;

            if (!$(button).hasClass(ClassName$1.BUTTON)) {
                button = $(button).closest(Selector$1.BUTTON)[0];
            }

            if (!button || button.hasAttribute('disabled') || button.classList.contains('disabled')) {
                event.preventDefault(); // work around Firefox bug #1540995
            } else {
                var inputBtn = button.querySelector(Selector$1.INPUT);

                if (inputBtn && (inputBtn.hasAttribute('disabled') || inputBtn.classList.contains('disabled'))) {
                    event.preventDefault(); // work around Firefox bug #1540995

                    return;
                }

                Button._jQueryInterface.call($(button), 'toggle');
            }
        }).on(Event$1.FOCUS_BLUR_DATA_API, Selector$1.DATA_TOGGLE_CARROT, function (event) {
            var button = $(event.target).closest(Selector$1.BUTTON)[0];
            $(button).toggleClass(ClassName$1.FOCUS, /^focus(in)?$/.test(event.type));
        });
        $(window).on(Event$1.LOAD_DATA_API, function () {
            // ensure correct active class is set to match the controls' actual values/states
            // find all checkboxes/readio buttons inside data-toggle groups
            var buttons = [].slice.call(document.querySelectorAll(Selector$1.DATA_TOGGLES_BUTTONS));

            for (var i = 0, len = buttons.length; i < len; i++) {
                var button = buttons[i];
                var input = button.querySelector(Selector$1.INPUT);

                if (input.checked || input.hasAttribute('checked')) {
                    button.classList.add(ClassName$1.ACTIVE);
                } else {
                    button.classList.remove(ClassName$1.ACTIVE);
                }
            } // find all button toggles


            buttons = [].slice.call(document.querySelectorAll(Selector$1.DATA_TOGGLE));

            for (var _i = 0, _len = buttons.length; _i < _len; _i++) {
                var _button = buttons[_i];

                if (_button.getAttribute('aria-pressed') === 'true') {
                    _button.classList.add(ClassName$1.ACTIVE);
                } else {
                    _button.classList.remove(ClassName$1.ACTIVE);
                }
            }
        });
        /**
         * ------------------------------------------------------------------------
         * jQuery
         * ------------------------------------------------------------------------
         */

        $.fn[NAME$1] = Button._jQueryInterface;
        $.fn[NAME$1].Constructor = Button;

        $.fn[NAME$1].noConflict = function () {
            $.fn[NAME$1] = JQUERY_NO_CONFLICT$1;
            return Button._jQueryInterface;
        };

        /**
         * ------------------------------------------------------------------------
         * Constants
         * ------------------------------------------------------------------------
         */

        var NAME$2 = 'carousel';
        var VERSION$2 = '4.4.1';
        var DATA_KEY$2 = 'bs.carousel';
        var EVENT_KEY$2 = "." + DATA_KEY$2;
        var DATA_API_KEY$2 = '.data-api';
        var JQUERY_NO_CONFLICT$2 = $.fn[NAME$2];
        var ARROW_LEFT_KEYCODE = 37; // KeyboardEvent.which value for left arrow key

        var ARROW_RIGHT_KEYCODE = 39; // KeyboardEvent.which value for right arrow key

        var TOUCHEVENT_COMPAT_WAIT = 500; // Time for mouse compat events to fire after touch

        var SWIPE_THRESHOLD = 40;
        var Default = {
            interval: 5000,
            keyboard: true,
            slide: false,
            pause: 'hover',
            wrap: true,
            touch: true
        };
        var DefaultType = {
            interval: '(number|boolean)',
            keyboard: 'boolean',
            slide: '(boolean|string)',
            pause: '(string|boolean)',
            wrap: 'boolean',
            touch: 'boolean'
        };
        var Direction = {
            NEXT: 'next',
            PREV: 'prev',
            LEFT: 'left',
            RIGHT: 'right'
        };
        var Event$2 = {
            SLIDE: "slide" + EVENT_KEY$2,
            SLID: "slid" + EVENT_KEY$2,
            KEYDOWN: "keydown" + EVENT_KEY$2,
            MOUSEENTER: "mouseenter" + EVENT_KEY$2,
            MOUSELEAVE: "mouseleave" + EVENT_KEY$2,
            TOUCHSTART: "touchstart" + EVENT_KEY$2,
            TOUCHMOVE: "touchmove" + EVENT_KEY$2,
            TOUCHEND: "touchend" + EVENT_KEY$2,
            POINTERDOWN: "pointerdown" + EVENT_KEY$2,
            POINTERUP: "pointerup" + EVENT_KEY$2,
            DRAG_START: "dragstart" + EVENT_KEY$2,
            LOAD_DATA_API: "load" + EVENT_KEY$2 + DATA_API_KEY$2,
            CLICK_DATA_API: "click" + EVENT_KEY$2 + DATA_API_KEY$2
        };
        var ClassName$2 = {
            CAROUSEL: 'carousel',
            ACTIVE: 'active',
            SLIDE: 'slide',
            RIGHT: 'carousel-item-right',
            LEFT: 'carousel-item-left',
            NEXT: 'carousel-item-next',
            PREV: 'carousel-item-prev',
            ITEM: 'carousel-item',
            POINTER_EVENT: 'pointer-event'
        };
        var Selector$2 = {
            ACTIVE: '.active',
            ACTIVE_ITEM: '.active.carousel-item',
            ITEM: '.carousel-item',
            ITEM_IMG: '.carousel-item img',
            NEXT_PREV: '.carousel-item-next, .carousel-item-prev',
            INDICATORS: '.carousel-indicators',
            DATA_SLIDE: '[data-slide], [data-slide-to]',
            DATA_RIDE: '[data-ride="carousel"]'
        };
        var PointerType = {
            TOUCH: 'touch',
            PEN: 'pen'
        };
        /**
         * ------------------------------------------------------------------------
         * Class Definition
         * ------------------------------------------------------------------------
         */

        var Carousel =
            /*#__PURE__*/
            function () {
                function Carousel(element, config) {
                    this._items = null;
                    this._interval = null;
                    this._activeElement = null;
                    this._isPaused = false;
                    this._isSliding = false;
                    this.touchTimeout = null;
                    this.touchStartX = 0;
                    this.touchDeltaX = 0;
                    this._config = this._getConfig(config);
                    this._element = element;
                    this._indicatorsElement = this._element.querySelector(Selector$2.INDICATORS);
                    this._touchSupported = 'ontouchstart' in document.documentElement || navigator.maxTouchPoints > 0;
                    this._pointerEvent = Boolean(window.PointerEvent || window.MSPointerEvent);

                    this._addEventListeners();
                } // Getters


                var _proto = Carousel.prototype;

                // Public
                _proto.next = function next() {
                    if (!this._isSliding) {
                        this._slide(Direction.NEXT);
                    }
                };

                _proto.nextWhenVisible = function nextWhenVisible() {
                    // Don't call next when the page isn't visible
                    // or the carousel or its parent isn't visible
                    if (!document.hidden && $(this._element).is(':visible') && $(this._element).css('visibility') !== 'hidden') {
                        this.next();
                    }
                };

                _proto.prev = function prev() {
                    if (!this._isSliding) {
                        this._slide(Direction.PREV);
                    }
                };

                _proto.pause = function pause(event) {
                    if (!event) {
                        this._isPaused = true;
                    }

                    if (this._element.querySelector(Selector$2.NEXT_PREV)) {
                        Util.triggerTransitionEnd(this._element);
                        this.cycle(true);
                    }

                    clearInterval(this._interval);
                    this._interval = null;
                };

                _proto.cycle = function cycle(event) {
                    if (!event) {
                        this._isPaused = false;
                    }

                    if (this._interval) {
                        clearInterval(this._interval);
                        this._interval = null;
                    }

                    if (this._config.interval && !this._isPaused) {
                        this._interval = setInterval((document.visibilityState ? this.nextWhenVisible : this.next).bind(this), this._config.interval);
                    }
                };

                _proto.to = function to(index) {
                    var _this = this;

                    this._activeElement = this._element.querySelector(Selector$2.ACTIVE_ITEM);

                    var activeIndex = this._getItemIndex(this._activeElement);

                    if (index > this._items.length - 1 || index < 0) {
                        return;
                    }

                    if (this._isSliding) {
                        $(this._element).one(Event$2.SLID, function () {
                            return _this.to(index);
                        });
                        return;
                    }

                    if (activeIndex === index) {
                        this.pause();
                        this.cycle();
                        return;
                    }

                    var direction = index > activeIndex ? Direction.NEXT : Direction.PREV;

                    this._slide(direction, this._items[index]);
                };

                _proto.dispose = function dispose() {
                    $(this._element).off(EVENT_KEY$2);
                    $.removeData(this._element, DATA_KEY$2);
                    this._items = null;
                    this._config = null;
                    this._element = null;
                    this._interval = null;
                    this._isPaused = null;
                    this._isSliding = null;
                    this._activeElement = null;
                    this._indicatorsElement = null;
                } // Private
                ;

                _proto._getConfig = function _getConfig(config) {
                    config = _objectSpread2({}, Default, {}, config);
                    Util.typeCheckConfig(NAME$2, config, DefaultType);
                    return config;
                };

                _proto._handleSwipe = function _handleSwipe() {
                    var absDeltax = Math.abs(this.touchDeltaX);

                    if (absDeltax <= SWIPE_THRESHOLD) {
                        return;
                    }

                    var direction = absDeltax / this.touchDeltaX;
                    this.touchDeltaX = 0; // swipe left

                    if (direction > 0) {
                        this.prev();
                    } // swipe right


                    if (direction < 0) {
                        this.next();
                    }
                };

                _proto._addEventListeners = function _addEventListeners() {
                    var _this2 = this;

                    if (this._config.keyboard) {
                        $(this._element).on(Event$2.KEYDOWN, function (event) {
                            return _this2._keydown(event);
                        });
                    }

                    if (this._config.pause === 'hover') {
                        $(this._element).on(Event$2.MOUSEENTER, function (event) {
                            return _this2.pause(event);
                        }).on(Event$2.MOUSELEAVE, function (event) {
                            return _this2.cycle(event);
                        });
                    }

                    if (this._config.touch) {
                        this._addTouchEventListeners();
                    }
                };

                _proto._addTouchEventListeners = function _addTouchEventListeners() {
                    var _this3 = this;

                    if (!this._touchSupported) {
                        return;
                    }

                    var start = function start(event) {
                        if (_this3._pointerEvent && PointerType[event.originalEvent.pointerType.toUpperCase()]) {
                            _this3.touchStartX = event.originalEvent.clientX;
                        } else if (!_this3._pointerEvent) {
                            _this3.touchStartX = event.originalEvent.touches[0].clientX;
                        }
                    };

                    var move = function move(event) {
                        // ensure swiping with one touch and not pinching
                        if (event.originalEvent.touches && event.originalEvent.touches.length > 1) {
                            _this3.touchDeltaX = 0;
                        } else {
                            _this3.touchDeltaX = event.originalEvent.touches[0].clientX - _this3.touchStartX;
                        }
                    };

                    var end = function end(event) {
                        if (_this3._pointerEvent && PointerType[event.originalEvent.pointerType.toUpperCase()]) {
                            _this3.touchDeltaX = event.originalEvent.clientX - _this3.touchStartX;
                        }

                        _this3._handleSwipe();

                        if (_this3._config.pause === 'hover') {
                            // If it's a touch-enabled device, mouseenter/leave are fired as
                            // part of the mouse compatibility events on first tap - the carousel
                            // would stop cycling until user tapped out of it;
                            // here, we listen for touchend, explicitly pause the carousel
                            // (as if it's the second time we tap on it, mouseenter compat event
                            // is NOT fired) and after a timeout (to allow for mouse compatibility
                            // events to fire) we explicitly restart cycling
                            _this3.pause();

                            if (_this3.touchTimeout) {
                                clearTimeout(_this3.touchTimeout);
                            }

                            _this3.touchTimeout = setTimeout(function (event) {
                                return _this3.cycle(event);
                            }, TOUCHEVENT_COMPAT_WAIT + _this3._config.interval);
                        }
                    };

                    $(this._element.querySelectorAll(Selector$2.ITEM_IMG)).on(Event$2.DRAG_START, function (e) {
                        return e.preventDefault();
                    });

                    if (this._pointerEvent) {
                        $(this._element).on(Event$2.POINTERDOWN, function (event) {
                            return start(event);
                        });
                        $(this._element).on(Event$2.POINTERUP, function (event) {
                            return end(event);
                        });

                        this._element.classList.add(ClassName$2.POINTER_EVENT);
                    } else {
                        $(this._element).on(Event$2.TOUCHSTART, function (event) {
                            return start(event);
                        });
                        $(this._element).on(Event$2.TOUCHMOVE, function (event) {
                            return move(event);
                        });
                        $(this._element).on(Event$2.TOUCHEND, function (event) {
                            return end(event);
                        });
                    }
                };

                _proto._keydown = function _keydown(event) {
                    if (/input|textarea/i.test(event.target.tagName)) {
                        return;
                    }

                    switch (event.which) {
                        case ARROW_LEFT_KEYCODE:
                            event.preventDefault();
                            this.prev();
                            break;

                        case ARROW_RIGHT_KEYCODE:
                            event.preventDefault();
                            this.next();
                            break;
                    }
                };

                _proto._getItemIndex = function _getItemIndex(element) {
                    this._items = element && element.parentNode ? [].slice.call(element.parentNode.querySelectorAll(Selector$2.ITEM)) : [];
                    return this._items.indexOf(element);
                };

                _proto._getItemByDirection = function _getItemByDirection(direction, activeElement) {
                    var isNextDirection = direction === Direction.NEXT;
                    var isPrevDirection = direction === Direction.PREV;

                    var activeIndex = this._getItemIndex(activeElement);

                    var lastItemIndex = this._items.length - 1;
                    var isGoingToWrap = isPrevDirection && activeIndex === 0 || isNextDirection && activeIndex === lastItemIndex;

                    if (isGoingToWrap && !this._config.wrap) {
                        return activeElement;
                    }

                    var delta = direction === Direction.PREV ? -1 : 1;
                    var itemIndex = (activeIndex + delta) % this._items.length;
                    return itemIndex === -1 ? this._items[this._items.length - 1] : this._items[itemIndex];
                };

                _proto._triggerSlideEvent = function _triggerSlideEvent(relatedTarget, eventDirectionName) {
                    var targetIndex = this._getItemIndex(relatedTarget);

                    var fromIndex = this._getItemIndex(this._element.querySelector(Selector$2.ACTIVE_ITEM));

                    var slideEvent = $.Event(Event$2.SLIDE, {
                        relatedTarget: relatedTarget,
                        direction: eventDirectionName,
                        from: fromIndex,
                        to: targetIndex
                    });
                    $(this._element).trigger(slideEvent);
                    return slideEvent;
                };

                _proto._setActiveIndicatorElement = function _setActiveIndicatorElement(element) {
                    if (this._indicatorsElement) {
                        var indicators = [].slice.call(this._indicatorsElement.querySelectorAll(Selector$2.ACTIVE));
                        $(indicators).removeClass(ClassName$2.ACTIVE);

                        var nextIndicator = this._indicatorsElement.children[this._getItemIndex(element)];

                        if (nextIndicator) {
                            $(nextIndicator).addClass(ClassName$2.ACTIVE);
                        }
                    }
                };

                _proto._slide = function _slide(direction, element) {
                    var _this4 = this;

                    var activeElement = this._element.querySelector(Selector$2.ACTIVE_ITEM);

                    var activeElementIndex = this._getItemIndex(activeElement);

                    var nextElement = element || activeElement && this._getItemByDirection(direction, activeElement);

                    var nextElementIndex = this._getItemIndex(nextElement);

                    var isCycling = Boolean(this._interval);
                    var directionalClassName;
                    var orderClassName;
                    var eventDirectionName;

                    if (direction === Direction.NEXT) {
                        directionalClassName = ClassName$2.LEFT;
                        orderClassName = ClassName$2.NEXT;
                        eventDirectionName = Direction.LEFT;
                    } else {
                        directionalClassName = ClassName$2.RIGHT;
                        orderClassName = ClassName$2.PREV;
                        eventDirectionName = Direction.RIGHT;
                    }

                    if (nextElement && $(nextElement).hasClass(ClassName$2.ACTIVE)) {
                        this._isSliding = false;
                        return;
                    }

                    var slideEvent = this._triggerSlideEvent(nextElement, eventDirectionName);

                    if (slideEvent.isDefaultPrevented()) {
                        return;
                    }

                    if (!activeElement || !nextElement) {
                        // Some weirdness is happening, so we bail
                        return;
                    }

                    this._isSliding = true;

                    if (isCycling) {
                        this.pause();
                    }

                    this._setActiveIndicatorElement(nextElement);

                    var slidEvent = $.Event(Event$2.SLID, {
                        relatedTarget: nextElement,
                        direction: eventDirectionName,
                        from: activeElementIndex,
                        to: nextElementIndex
                    });

                    if ($(this._element).hasClass(ClassName$2.SLIDE)) {
                        $(nextElement).addClass(orderClassName);
                        Util.reflow(nextElement);
                        $(activeElement).addClass(directionalClassName);
                        $(nextElement).addClass(directionalClassName);
                        var nextElementInterval = parseInt(nextElement.getAttribute('data-interval'), 10);

                        if (nextElementInterval) {
                            this._config.defaultInterval = this._config.defaultInterval || this._config.interval;
                            this._config.interval = nextElementInterval;
                        } else {
                            this._config.interval = this._config.defaultInterval || this._config.interval;
                        }

                        var transitionDuration = Util.getTransitionDurationFromElement(activeElement);
                        $(activeElement).one(Util.TRANSITION_END, function () {
                            $(nextElement).removeClass(directionalClassName + " " + orderClassName).addClass(ClassName$2.ACTIVE);
                            $(activeElement).removeClass(ClassName$2.ACTIVE + " " + orderClassName + " " + directionalClassName);
                            _this4._isSliding = false;
                            setTimeout(function () {
                                return $(_this4._element).trigger(slidEvent);
                            }, 0);
                        }).emulateTransitionEnd(transitionDuration);
                    } else {
                        $(activeElement).removeClass(ClassName$2.ACTIVE);
                        $(nextElement).addClass(ClassName$2.ACTIVE);
                        this._isSliding = false;
                        $(this._element).trigger(slidEvent);
                    }

                    if (isCycling) {
                        this.cycle();
                    }
                } // Static
                ;

                Carousel._jQueryInterface = function _jQueryInterface(config) {
                    return this.each(function () {
                        var data = $(this).data(DATA_KEY$2);

                        var _config = _objectSpread2({}, Default, {}, $(this).data());

                        if (typeof config === 'object') {
                            _config = _objectSpread2({}, _config, {}, config);
                        }

                        var action = typeof config === 'string' ? config : _config.slide;

                        if (!data) {
                            data = new Carousel(this, _config);
                            $(this).data(DATA_KEY$2, data);
                        }

                        if (typeof config === 'number') {
                            data.to(config);
                        } else if (typeof action === 'string') {
                            if (typeof data[action] === 'undefined') {
                                throw new TypeError("No method named \"" + action + "\"");
                            }

                            data[action]();
                        } else if (_config.interval && _config.ride) {
                            data.pause();
                            data.cycle();
                        }
                    });
                };

                Carousel._dataApiClickHandler = function _dataApiClickHandler(event) {
                    var selector = Util.getSelectorFromElement(this);

                    if (!selector) {
                        return;
                    }

                    var target = $(selector)[0];

                    if (!target || !$(target).hasClass(ClassName$2.CAROUSEL)) {
                        return;
                    }

                    var config = _objectSpread2({}, $(target).data(), {}, $(this).data());

                    var slideIndex = this.getAttribute('data-slide-to');

                    if (slideIndex) {
                        config.interval = false;
                    }

                    Carousel._jQueryInterface.call($(target), config);

                    if (slideIndex) {
                        $(target).data(DATA_KEY$2).to(slideIndex);
                    }

                    event.preventDefault();
                };

                _createClass(Carousel, null, [{
                    key: "VERSION",
                    get: function get() {
                        return VERSION$2;
                    }
                }, {
                    key: "Default",
                    get: function get() {
                        return Default;
                    }
                }]);

                return Carousel;
            }();
        /**
         * ------------------------------------------------------------------------
         * Data Api implementation
         * ------------------------------------------------------------------------
         */


        $(document).on(Event$2.CLICK_DATA_API, Selector$2.DATA_SLIDE, Carousel._dataApiClickHandler);
        $(window).on(Event$2.LOAD_DATA_API, function () {
            var carousels = [].slice.call(document.querySelectorAll(Selector$2.DATA_RIDE));

            for (var i = 0, len = carousels.length; i < len; i++) {
                var $carousel = $(carousels[i]);

                Carousel._jQueryInterface.call($carousel, $carousel.data());
            }
        });
        /**
         * ------------------------------------------------------------------------
         * jQuery
         * ------------------------------------------------------------------------
         */

        $.fn[NAME$2] = Carousel._jQueryInterface;
        $.fn[NAME$2].Constructor = Carousel;

        $.fn[NAME$2].noConflict = function () {
            $.fn[NAME$2] = JQUERY_NO_CONFLICT$2;
            return Carousel._jQueryInterface;
        };

        /**
         * ------------------------------------------------------------------------
         * Constants
         * ------------------------------------------------------------------------
         */

        var NAME$3 = 'collapse';
        var VERSION$3 = '4.4.1';
        var DATA_KEY$3 = 'bs.collapse';
        var EVENT_KEY$3 = "." + DATA_KEY$3;
        var DATA_API_KEY$3 = '.data-api';
        var JQUERY_NO_CONFLICT$3 = $.fn[NAME$3];
        var Default$1 = {
            toggle: true,
            parent: ''
        };
        var DefaultType$1 = {
            toggle: 'boolean',
            parent: '(string|element)'
        };
        var Event$3 = {
            SHOW: "show" + EVENT_KEY$3,
            SHOWN: "shown" + EVENT_KEY$3,
            HIDE: "hide" + EVENT_KEY$3,
            HIDDEN: "hidden" + EVENT_KEY$3,
            CLICK_DATA_API: "click" + EVENT_KEY$3 + DATA_API_KEY$3
        };
        var ClassName$3 = {
            SHOW: 'show',
            COLLAPSE: 'collapse',
            COLLAPSING: 'collapsing',
            COLLAPSED: 'collapsed'
        };
        var Dimension = {
            WIDTH: 'width',
            HEIGHT: 'height'
        };
        var Selector$3 = {
            ACTIVES: '.show, .collapsing',
            DATA_TOGGLE: '[data-toggle="collapse"]'
        };
        /**
         * ------------------------------------------------------------------------
         * Class Definition
         * ------------------------------------------------------------------------
         */

        var Collapse =
            /*#__PURE__*/
            function () {
                function Collapse(element, config) {
                    this._isTransitioning = false;
                    this._element = element;
                    this._config = this._getConfig(config);
                    this._triggerArray = [].slice.call(document.querySelectorAll("[data-toggle=\"collapse\"][href=\"#" + element.id + "\"]," + ("[data-toggle=\"collapse\"][data-target=\"#" + element.id + "\"]")));
                    var toggleList = [].slice.call(document.querySelectorAll(Selector$3.DATA_TOGGLE));

                    for (var i = 0, len = toggleList.length; i < len; i++) {
                        var elem = toggleList[i];
                        var selector = Util.getSelectorFromElement(elem);
                        var filterElement = [].slice.call(document.querySelectorAll(selector)).filter(function (foundElem) {
                            return foundElem === element;
                        });

                        if (selector !== null && filterElement.length > 0) {
                            this._selector = selector;

                            this._triggerArray.push(elem);
                        }
                    }

                    this._parent = this._config.parent ? this._getParent() : null;

                    if (!this._config.parent) {
                        this._addAriaAndCollapsedClass(this._element, this._triggerArray);
                    }

                    if (this._config.toggle) {
                        this.toggle();
                    }
                } // Getters


                var _proto = Collapse.prototype;

                // Public
                _proto.toggle = function toggle() {
                    if ($(this._element).hasClass(ClassName$3.SHOW)) {
                        this.hide();
                    } else {
                        this.show();
                    }
                };

                _proto.show = function show() {
                    var _this = this;

                    if (this._isTransitioning || $(this._element).hasClass(ClassName$3.SHOW)) {
                        return;
                    }

                    var actives;
                    var activesData;

                    if (this._parent) {
                        actives = [].slice.call(this._parent.querySelectorAll(Selector$3.ACTIVES)).filter(function (elem) {
                            if (typeof _this._config.parent === 'string') {
                                return elem.getAttribute('data-parent') === _this._config.parent;
                            }

                            return elem.classList.contains(ClassName$3.COLLAPSE);
                        });

                        if (actives.length === 0) {
                            actives = null;
                        }
                    }

                    if (actives) {
                        activesData = $(actives).not(this._selector).data(DATA_KEY$3);

                        if (activesData && activesData._isTransitioning) {
                            return;
                        }
                    }

                    var startEvent = $.Event(Event$3.SHOW);
                    $(this._element).trigger(startEvent);

                    if (startEvent.isDefaultPrevented()) {
                        return;
                    }

                    if (actives) {
                        Collapse._jQueryInterface.call($(actives).not(this._selector), 'hide');

                        if (!activesData) {
                            $(actives).data(DATA_KEY$3, null);
                        }
                    }

                    var dimension = this._getDimension();

                    $(this._element).removeClass(ClassName$3.COLLAPSE).addClass(ClassName$3.COLLAPSING);
                    this._element.style[dimension] = 0;

                    if (this._triggerArray.length) {
                        $(this._triggerArray).removeClass(ClassName$3.COLLAPSED).attr('aria-expanded', true);
                    }

                    this.setTransitioning(true);

                    var complete = function complete() {
                        $(_this._element).removeClass(ClassName$3.COLLAPSING).addClass(ClassName$3.COLLAPSE).addClass(ClassName$3.SHOW);
                        _this._element.style[dimension] = '';

                        _this.setTransitioning(false);

                        $(_this._element).trigger(Event$3.SHOWN);
                    };

                    var capitalizedDimension = dimension[0].toUpperCase() + dimension.slice(1);
                    var scrollSize = "scroll" + capitalizedDimension;
                    var transitionDuration = Util.getTransitionDurationFromElement(this._element);
                    $(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
                    this._element.style[dimension] = this._element[scrollSize] + "px";
                };

                _proto.hide = function hide() {
                    var _this2 = this;

                    if (this._isTransitioning || !$(this._element).hasClass(ClassName$3.SHOW)) {
                        return;
                    }

                    var startEvent = $.Event(Event$3.HIDE);
                    $(this._element).trigger(startEvent);

                    if (startEvent.isDefaultPrevented()) {
                        return;
                    }

                    var dimension = this._getDimension();

                    this._element.style[dimension] = this._element.getBoundingClientRect()[dimension] + "px";
                    Util.reflow(this._element);
                    $(this._element).addClass(ClassName$3.COLLAPSING).removeClass(ClassName$3.COLLAPSE).removeClass(ClassName$3.SHOW);
                    var triggerArrayLength = this._triggerArray.length;

                    if (triggerArrayLength > 0) {
                        for (var i = 0; i < triggerArrayLength; i++) {
                            var trigger = this._triggerArray[i];
                            var selector = Util.getSelectorFromElement(trigger);

                            if (selector !== null) {
                                var $elem = $([].slice.call(document.querySelectorAll(selector)));

                                if (!$elem.hasClass(ClassName$3.SHOW)) {
                                    $(trigger).addClass(ClassName$3.COLLAPSED).attr('aria-expanded', false);
                                }
                            }
                        }
                    }

                    this.setTransitioning(true);

                    var complete = function complete() {
                        _this2.setTransitioning(false);

                        $(_this2._element).removeClass(ClassName$3.COLLAPSING).addClass(ClassName$3.COLLAPSE).trigger(Event$3.HIDDEN);
                    };

                    this._element.style[dimension] = '';
                    var transitionDuration = Util.getTransitionDurationFromElement(this._element);
                    $(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
                };

                _proto.setTransitioning = function setTransitioning(isTransitioning) {
                    this._isTransitioning = isTransitioning;
                };

                _proto.dispose = function dispose() {
                    $.removeData(this._element, DATA_KEY$3);
                    this._config = null;
                    this._parent = null;
                    this._element = null;
                    this._triggerArray = null;
                    this._isTransitioning = null;
                } // Private
                ;

                _proto._getConfig = function _getConfig(config) {
                    config = _objectSpread2({}, Default$1, {}, config);
                    config.toggle = Boolean(config.toggle); // Coerce string values

                    Util.typeCheckConfig(NAME$3, config, DefaultType$1);
                    return config;
                };

                _proto._getDimension = function _getDimension() {
                    var hasWidth = $(this._element).hasClass(Dimension.WIDTH);
                    return hasWidth ? Dimension.WIDTH : Dimension.HEIGHT;
                };

                _proto._getParent = function _getParent() {
                    var _this3 = this;

                    var parent;

                    if (Util.isElement(this._config.parent)) {
                        parent = this._config.parent; // It's a jQuery object

                        if (typeof this._config.parent.jquery !== 'undefined') {
                            parent = this._config.parent[0];
                        }
                    } else {
                        parent = document.querySelector(this._config.parent);
                    }

                    var selector = "[data-toggle=\"collapse\"][data-parent=\"" + this._config.parent + "\"]";
                    var children = [].slice.call(parent.querySelectorAll(selector));
                    $(children).each(function (i, element) {
                        _this3._addAriaAndCollapsedClass(Collapse._getTargetFromElement(element), [element]);
                    });
                    return parent;
                };

                _proto._addAriaAndCollapsedClass = function _addAriaAndCollapsedClass(element, triggerArray) {
                    var isOpen = $(element).hasClass(ClassName$3.SHOW);

                    if (triggerArray.length) {
                        $(triggerArray).toggleClass(ClassName$3.COLLAPSED, !isOpen).attr('aria-expanded', isOpen);
                    }
                } // Static
                ;

                Collapse._getTargetFromElement = function _getTargetFromElement(element) {
                    var selector = Util.getSelectorFromElement(element);
                    return selector ? document.querySelector(selector) : null;
                };

                Collapse._jQueryInterface = function _jQueryInterface(config) {
                    return this.each(function () {
                        var $this = $(this);
                        var data = $this.data(DATA_KEY$3);

                        var _config = _objectSpread2({}, Default$1, {}, $this.data(), {}, typeof config === 'object' && config ? config : {});

                        if (!data && _config.toggle && /show|hide/.test(config)) {
                            _config.toggle = false;
                        }

                        if (!data) {
                            data = new Collapse(this, _config);
                            $this.data(DATA_KEY$3, data);
                        }

                        if (typeof config === 'string') {
                            if (typeof data[config] === 'undefined') {
                                throw new TypeError("No method named \"" + config + "\"");
                            }

                            data[config]();
                        }
                    });
                };

                _createClass(Collapse, null, [{
                    key: "VERSION",
                    get: function get() {
                        return VERSION$3;
                    }
                }, {
                    key: "Default",
                    get: function get() {
                        return Default$1;
                    }
                }]);

                return Collapse;
            }();
        /**
         * ------------------------------------------------------------------------
         * Data Api implementation
         * ------------------------------------------------------------------------
         */


        $(document).on(Event$3.CLICK_DATA_API, Selector$3.DATA_TOGGLE, function (event) {
            // preventDefault only for <a> elements (which change the URL) not inside the collapsible element
            if (event.currentTarget.tagName === 'A') {
                event.preventDefault();
            }

            var $trigger = $(this);
            var selector = Util.getSelectorFromElement(this);
            var selectors = [].slice.call(document.querySelectorAll(selector));
            $(selectors).each(function () {
                var $target = $(this);
                var data = $target.data(DATA_KEY$3);
                var config = data ? 'toggle' : $trigger.data();

                Collapse._jQueryInterface.call($target, config);
            });
        });
        /**
         * ------------------------------------------------------------------------
         * jQuery
         * ------------------------------------------------------------------------
         */

        $.fn[NAME$3] = Collapse._jQueryInterface;
        $.fn[NAME$3].Constructor = Collapse;

        $.fn[NAME$3].noConflict = function () {
            $.fn[NAME$3] = JQUERY_NO_CONFLICT$3;
            return Collapse._jQueryInterface;
        };

        /**
         * ------------------------------------------------------------------------
         * Constants
         * ------------------------------------------------------------------------
         */

        var NAME$4 = 'nm_dropdown';
        var VERSION$4 = '4.4.1';
        var DATA_KEY$4 = 'bs.dropdown';
        var EVENT_KEY$4 = "." + DATA_KEY$4;
        var DATA_API_KEY$4 = '.data-api';
        var JQUERY_NO_CONFLICT$4 = $.fn[NAME$4];
        var ESCAPE_KEYCODE = 27; // KeyboardEvent.which value for Escape (Esc) key

        var SPACE_KEYCODE = 32; // KeyboardEvent.which value for space key

        var TAB_KEYCODE = 9; // KeyboardEvent.which value for tab key

        var ARROW_UP_KEYCODE = 38; // KeyboardEvent.which value for up arrow key

        var ARROW_DOWN_KEYCODE = 40; // KeyboardEvent.which value for down arrow key

        var RIGHT_MOUSE_BUTTON_WHICH = 3; // MouseEvent.which value for the right button (assuming a right-handed mouse)

        var REGEXP_KEYDOWN = new RegExp(ARROW_UP_KEYCODE + "|" + ARROW_DOWN_KEYCODE + "|" + ESCAPE_KEYCODE);
        var Event$4 = {
            HIDE: "hide" + EVENT_KEY$4,
            HIDDEN: "hidden" + EVENT_KEY$4,
            SHOW: "show" + EVENT_KEY$4,
            SHOWN: "shown" + EVENT_KEY$4,
            CLICK: "click" + EVENT_KEY$4,
            CLICK_DATA_API: "click" + EVENT_KEY$4 + DATA_API_KEY$4,
            KEYDOWN_DATA_API: "keydown" + EVENT_KEY$4 + DATA_API_KEY$4,
            KEYUP_DATA_API: "keyup" + EVENT_KEY$4 + DATA_API_KEY$4
        };
        var ClassName$4 = {
            DISABLED: 'disabled',
            SHOW: 'show',
            DROPUP: 'nm_dropup',
            DROPRIGHT: 'nm_dropright',
            DROPLEFT: 'nm_dropleft',
            MENURIGHT: 'nm_dropdown-menu-right',
            MENULEFT: 'nm_dropdown-menu-left',
            POSITION_STATIC: 'position-static'
        };
        var Selector$4 = {
            DATA_TOGGLE: '[data-toggle="nm_dropdown"]',
            FORM_CHILD: '.nm_dropdown form',
            MENU: '.nm_dropdown-menu',
            NAVBAR_NAV: '.nm_navbar-nav',
            VISIBLE_ITEMS: '.nm_dropdown-menu .nm_dropdown-item:not(.disabled):not(:disabled)'
        };
        var AttachmentMap = {
            TOP: 'top-start',
            TOPEND: 'top-end',
            BOTTOM: 'bottom-start',
            BOTTOMEND: 'bottom-end',
            RIGHT: 'right-start',
            RIGHTEND: 'right-end',
            LEFT: 'left-start',
            LEFTEND: 'left-end'
        };
        var Default$2 = {
            offset: 0,
            flip: true,
            boundary: 'scrollParent',
            reference: 'toggle',
            display: 'dynamic',
            popperConfig: null
        };
        var DefaultType$2 = {
            offset: '(number|string|function)',
            flip: 'boolean',
            boundary: '(string|element)',
            reference: '(string|element)',
            display: 'string',
            popperConfig: '(null|object)'
        };
        /**
         * ------------------------------------------------------------------------
         * Class Definition
         * ------------------------------------------------------------------------
         */

        var Dropdown =
            /*#__PURE__*/
            function () {
                function Dropdown(element, config) {
                    this._element = element;
                    this._popper = null;
                    this._config = this._getConfig(config);
                    this._menu = this._getMenuElement();
                    this._inNavbar = this._detectNavbar();

                    this._addEventListeners();
                } // Getters


                var _proto = Dropdown.prototype;

                // Public
                _proto.toggle = function toggle() {
                    if (this._element.disabled || $(this._element).hasClass(ClassName$4.DISABLED)) {
                        return;
                    }

                    var isActive = $(this._menu).hasClass(ClassName$4.SHOW);

                    Dropdown._clearMenus();

                    if (isActive) {
                        return;
                    }

                    this.show(true);
                };

                _proto.show = function show(usePopper) {
                    if (usePopper === void 0) {
                        usePopper = false;
                    }

                    if (this._element.disabled || $(this._element).hasClass(ClassName$4.DISABLED) || $(this._menu).hasClass(ClassName$4.SHOW)) {
                        return;
                    }

                    var relatedTarget = {
                        relatedTarget: this._element
                    };
                    var showEvent = $.Event(Event$4.SHOW, relatedTarget);

                    var parent = Dropdown._getParentFromElement(this._element);

                    $(parent).trigger(showEvent);

                    if (showEvent.isDefaultPrevented()) {
                        return;
                    } // Disable totally Popper.js for Dropdown in Navbar


                    if (!this._inNavbar && usePopper) {
                        /**
                         * Check for Popper dependency
                         * Popper - https://popper.js.org
                         */
                        if (typeof Popper === 'undefined') {
                            throw new TypeError('Bootstrap\'s dropdowns require Popper.js (https://popper.js.org/)');
                        }

                        var referenceElement = this._element;

                        if (this._config.reference === 'parent') {
                            referenceElement = parent;
                        } else if (Util.isElement(this._config.reference)) {
                            referenceElement = this._config.reference; // Check if it's jQuery element

                            if (typeof this._config.reference.jquery !== 'undefined') {
                                referenceElement = this._config.reference[0];
                            }
                        } // If boundary is not `scrollParent`, then set position to `static`
                        // to allow the menu to "escape" the scroll parent's boundaries
                        // https://github.com/twbs/bootstrap/issues/24251


                        if (this._config.boundary !== 'scrollParent') {
                            $(parent).addClass(ClassName$4.POSITION_STATIC);
                        }

                        this._popper = new Popper(referenceElement, this._menu, this._getPopperConfig());
                    } // If this is a touch-enabled device we add extra
                    // empty mouseover listeners to the body's immediate children;
                    // only needed because of broken event delegation on iOS
                    // https://www.quirksmode.org/blog/archives/2014/02/mouse_event_bub.html


                    if ('ontouchstart' in document.documentElement && $(parent).closest(Selector$4.NAVBAR_NAV).length === 0) {
                        $(document.body).children().on('mouseover', null, $.noop);
                    }

                    this._element.focus();

                    this._element.setAttribute('aria-expanded', true);

                    $(this._menu).toggleClass(ClassName$4.SHOW);
                    $(parent).toggleClass(ClassName$4.SHOW).trigger($.Event(Event$4.SHOWN, relatedTarget));
                };

                _proto.hide = function hide() {
                    if (this._element.disabled || $(this._element).hasClass(ClassName$4.DISABLED) || !$(this._menu).hasClass(ClassName$4.SHOW)) {
                        return;
                    }

                    var relatedTarget = {
                        relatedTarget: this._element
                    };
                    var hideEvent = $.Event(Event$4.HIDE, relatedTarget);

                    var parent = Dropdown._getParentFromElement(this._element);

                    $(parent).trigger(hideEvent);

                    if (hideEvent.isDefaultPrevented()) {
                        return;
                    }

                    if (this._popper) {
                        this._popper.destroy();
                    }

                    $(this._menu).toggleClass(ClassName$4.SHOW);
                    $(parent).toggleClass(ClassName$4.SHOW).trigger($.Event(Event$4.HIDDEN, relatedTarget));
                };

                _proto.dispose = function dispose() {
                    $.removeData(this._element, DATA_KEY$4);
                    $(this._element).off(EVENT_KEY$4);
                    this._element = null;
                    this._menu = null;

                    if (this._popper !== null) {
                        this._popper.destroy();

                        this._popper = null;
                    }
                };

                _proto.update = function update() {
                    this._inNavbar = this._detectNavbar();

                    if (this._popper !== null) {
                        this._popper.scheduleUpdate();
                    }
                } // Private
                ;

                _proto._addEventListeners = function _addEventListeners() {
                    var _this = this;

                    $(this._element).on(Event$4.CLICK, function (event) {
                        event.preventDefault();
                        event.stopPropagation();

                        _this.toggle();
                    });
                };

                _proto._getConfig = function _getConfig(config) {
                    config = _objectSpread2({}, this.constructor.Default, {}, $(this._element).data(), {}, config);
                    Util.typeCheckConfig(NAME$4, config, this.constructor.DefaultType);
                    return config;
                };

                _proto._getMenuElement = function _getMenuElement() {
                    if (!this._menu) {
                        var parent = Dropdown._getParentFromElement(this._element);

                        if (parent) {
                            this._menu = parent.querySelector(Selector$4.MENU);
                        }
                    }

                    return this._menu;
                };

                _proto._getPlacement = function _getPlacement() {
                    var $parentDropdown = $(this._element.parentNode);
                    var placement = AttachmentMap.BOTTOM; // Handle dropup

                    if ($parentDropdown.hasClass(ClassName$4.DROPUP)) {
                        placement = AttachmentMap.TOP;

                        if ($(this._menu).hasClass(ClassName$4.MENURIGHT)) {
                            placement = AttachmentMap.TOPEND;
                        }
                    } else if ($parentDropdown.hasClass(ClassName$4.DROPRIGHT)) {
                        placement = AttachmentMap.RIGHT;
                    } else if ($parentDropdown.hasClass(ClassName$4.DROPLEFT)) {
                        placement = AttachmentMap.LEFT;
                    } else if ($(this._menu).hasClass(ClassName$4.MENURIGHT)) {
                        placement = AttachmentMap.BOTTOMEND;
                    }

                    return placement;
                };

                _proto._detectNavbar = function _detectNavbar() {
                    return $(this._element).closest('.nm_navbar').length > 0;
                };

                _proto._getOffset = function _getOffset() {
                    var _this2 = this;

                    var offset = {};

                    if (typeof this._config.offset === 'function') {
                        offset.fn = function (data) {
                            data.offsets = _objectSpread2({}, data.offsets, {}, _this2._config.offset(data.offsets, _this2._element) || {});
                            return data;
                        };
                    } else {
                        offset.offset = this._config.offset;
                    }

                    return offset;
                };

                _proto._getPopperConfig = function _getPopperConfig() {
                    var popperConfig = {
                        placement: this._getPlacement(),
                        modifiers: {
                            offset: this._getOffset(),
                            flip: {
                                enabled: this._config.flip
                            },
                            preventOverflow: {
                                boundariesElement: this._config.boundary
                            }
                        }
                    }; // Disable Popper.js if we have a static display

                    if (this._config.display === 'static') {
                        popperConfig.modifiers.applyStyle = {
                            enabled: false
                        };
                    }

                    return _objectSpread2({}, popperConfig, {}, this._config.popperConfig);
                } // Static
                ;

                Dropdown._jQueryInterface = function _jQueryInterface(config) {
                    return this.each(function () {
                        var data = $(this).data(DATA_KEY$4);

                        var _config = typeof config === 'object' ? config : null;

                        if (!data) {
                            data = new Dropdown(this, _config);
                            $(this).data(DATA_KEY$4, data);
                        }

                        if (typeof config === 'string') {
                            if (typeof data[config] === 'undefined') {
                                throw new TypeError("No method named \"" + config + "\"");
                            }

                            data[config]();
                        }
                    });
                };

                Dropdown._clearMenus = function _clearMenus(event) {
                    if (event && (event.which === RIGHT_MOUSE_BUTTON_WHICH || event.type === 'keyup' && event.which !== TAB_KEYCODE)) {
                        return;
                    }

                    var toggles = [].slice.call(document.querySelectorAll(Selector$4.DATA_TOGGLE));

                    for (var i = 0, len = toggles.length; i < len; i++) {
                        var parent = Dropdown._getParentFromElement(toggles[i]);

                        var context = $(toggles[i]).data(DATA_KEY$4);
                        var relatedTarget = {
                            relatedTarget: toggles[i]
                        };

                        if (event && event.type === 'click') {
                            relatedTarget.clickEvent = event;
                        }

                        if (!context) {
                            continue;
                        }

                        var dropdownMenu = context._menu;

                        if (!$(parent).hasClass(ClassName$4.SHOW)) {
                            continue;
                        }

                        if (event && (event.type === 'click' && /input|textarea/i.test(event.target.tagName) || event.type === 'keyup' && event.which === TAB_KEYCODE) && $.contains(parent, event.target)) {
                            continue;
                        }

                        var hideEvent = $.Event(Event$4.HIDE, relatedTarget);
                        $(parent).trigger(hideEvent);

                        if (hideEvent.isDefaultPrevented()) {
                            continue;
                        } // If this is a touch-enabled device we remove the extra
                        // empty mouseover listeners we added for iOS support


                        if ('ontouchstart' in document.documentElement) {
                            $(document.body).children().off('mouseover', null, $.noop);
                        }

                        toggles[i].setAttribute('aria-expanded', 'false');

                        if (context._popper) {
                            context._popper.destroy();
                        }

                        $(dropdownMenu).removeClass(ClassName$4.SHOW);
                        $(parent).removeClass(ClassName$4.SHOW).trigger($.Event(Event$4.HIDDEN, relatedTarget));
                    }
                };

                Dropdown._getParentFromElement = function _getParentFromElement(element) {
                    var parent;
                    var selector = Util.getSelectorFromElement(element);

                    if (selector) {
                        parent = document.querySelector(selector);
                    }

                    return parent || element.parentNode;
                } // eslint-disable-next-line complexity
                ;

                Dropdown._dataApiKeydownHandler = function _dataApiKeydownHandler(event) {
                    // If not input/textarea:
                    //  - And not a key in REGEXP_KEYDOWN => not a dropdown command
                    // If input/textarea:
                    //  - If space key => not a dropdown command
                    //  - If key is other than escape
                    //    - If key is not up or down => not a dropdown command
                    //    - If trigger inside the menu => not a dropdown command
                    if (/input|textarea/i.test(event.target.tagName) ? event.which === SPACE_KEYCODE || event.which !== ESCAPE_KEYCODE && (event.which !== ARROW_DOWN_KEYCODE && event.which !== ARROW_UP_KEYCODE || $(event.target).closest(Selector$4.MENU).length) : !REGEXP_KEYDOWN.test(event.which)) {
                        return;
                    }

                    event.preventDefault();
                    event.stopPropagation();

                    if (this.disabled || $(this).hasClass(ClassName$4.DISABLED)) {
                        return;
                    }

                    var parent = Dropdown._getParentFromElement(this);

                    var isActive = $(parent).hasClass(ClassName$4.SHOW);

                    if (!isActive && event.which === ESCAPE_KEYCODE) {
                        return;
                    }

                    if (!isActive || isActive && (event.which === ESCAPE_KEYCODE || event.which === SPACE_KEYCODE)) {
                        if (event.which === ESCAPE_KEYCODE) {
                            var toggle = parent.querySelector(Selector$4.DATA_TOGGLE);
                            $(toggle).trigger('focus');
                        }

                        $(this).trigger('click');
                        return;
                    }

                    var items = [].slice.call(parent.querySelectorAll(Selector$4.VISIBLE_ITEMS)).filter(function (item) {
                        return $(item).is(':visible');
                    });

                    if (items.length === 0) {
                        return;
                    }

                    var index = items.indexOf(event.target);

                    if (event.which === ARROW_UP_KEYCODE && index > 0) {
                        // Up
                        index--;
                    }

                    if (event.which === ARROW_DOWN_KEYCODE && index < items.length - 1) {
                        // Down
                        index++;
                    }

                    if (index < 0) {
                        index = 0;
                    }

                    items[index].focus();
                };

                _createClass(Dropdown, null, [{
                    key: "VERSION",
                    get: function get() {
                        return VERSION$4;
                    }
                }, {
                    key: "Default",
                    get: function get() {
                        return Default$2;
                    }
                }, {
                    key: "DefaultType",
                    get: function get() {
                        return DefaultType$2;
                    }
                }]);

                return Dropdown;
            }();
        /**
         * ------------------------------------------------------------------------
         * Data Api implementation
         * ------------------------------------------------------------------------
         */


        $(document).on(Event$4.KEYDOWN_DATA_API, Selector$4.DATA_TOGGLE, Dropdown._dataApiKeydownHandler).on(Event$4.KEYDOWN_DATA_API, Selector$4.MENU, Dropdown._dataApiKeydownHandler).on(Event$4.CLICK_DATA_API + " " + Event$4.KEYUP_DATA_API, Dropdown._clearMenus).on(Event$4.CLICK_DATA_API, Selector$4.DATA_TOGGLE, function (event) {
            event.preventDefault();
            event.stopPropagation();
            Dropdown._jQueryInterface.call($(this), 'toggle');
        }).on(Event$4.CLICK_DATA_API, Selector$4.FORM_CHILD, function (e) {
            e.stopPropagation();
        });
        /**
         * ------------------------------------------------------------------------
         * jQuery
         * ------------------------------------------------------------------------
         */

        $.fn[NAME$4] = Dropdown._jQueryInterface;
        $.fn[NAME$4].Constructor = Dropdown;

        $.fn[NAME$4].noConflict = function () {
            $.fn[NAME$4] = JQUERY_NO_CONFLICT$4;
            return Dropdown._jQueryInterface;
        };

        /**
         * ------------------------------------------------------------------------
         * Constants
         * ------------------------------------------------------------------------
         */

        var NAME$5 = 'nm_modal';
        var VERSION$5 = '4.4.1';
        var DATA_KEY$5 = 'bs.modal';
        var EVENT_KEY$5 = "." + DATA_KEY$5;
        var DATA_API_KEY$5 = '.data-api';
        var JQUERY_NO_CONFLICT$5 = $.fn[NAME$5];
        var ESCAPE_KEYCODE$1 = 27; // KeyboardEvent.which value for Escape (Esc) key

        var Default$3 = {
            backdrop: true,
            keyboard: true,
            focus: true,
            show: true
        };
        var DefaultType$3 = {
            backdrop: '(boolean|string)',
            keyboard: 'boolean',
            focus: 'boolean',
            show: 'boolean'
        };
        var Event$5 = {
            HIDE: "hide" + EVENT_KEY$5,
            HIDE_PREVENTED: "hidePrevented" + EVENT_KEY$5,
            HIDDEN: "hidden" + EVENT_KEY$5,
            SHOW: "show" + EVENT_KEY$5,
            SHOWN: "shown" + EVENT_KEY$5,
            FOCUSIN: "focusin" + EVENT_KEY$5,
            RESIZE: "resize" + EVENT_KEY$5,
            CLICK_DISMISS: "click.dismiss" + EVENT_KEY$5,
            KEYDOWN_DISMISS: "keydown.dismiss" + EVENT_KEY$5,
            MOUSEUP_DISMISS: "mouseup.dismiss" + EVENT_KEY$5,
            MOUSEDOWN_DISMISS: "mousedown.dismiss" + EVENT_KEY$5,
            CLICK_DATA_API: "click" + EVENT_KEY$5 + DATA_API_KEY$5
        };
        var ClassName$5 = {
            SCROLLABLE: 'nm_modal-dialog-scrollable',
            SCROLLBAR_MEASURER: 'nm_modal-scrollbar-measure',
            BACKDROP: 'nm_modal-backdrop',
            OPEN: 'nm_modal-open',
            FADE: 'fade',
            SHOW: 'show',
            STATIC: 'nm_modal-static'
        };
        var Selector$5 = {
            DIALOG: '.nm_modal-dialog',
            MODAL_BODY: '.nm_modal-body',
            DATA_TOGGLE: '[data-toggle="nm_modal"]',
            DATA_DISMISS: '[data-dismiss="nm_modal"]',
            FIXED_CONTENT: '.fixed-top, .fixed-bottom, .is-fixed, .sticky-top',
            STICKY_CONTENT: '.sticky-top'
        };
        /**
         * ------------------------------------------------------------------------
         * Class Definition
         * ------------------------------------------------------------------------
         */

        var Modal =
            /*#__PURE__*/
            function () {
                function Modal(element, config) {
                    this._config = this._getConfig(config);
                    this._element = element;
                    this._dialog = element.querySelector(Selector$5.DIALOG);
                    this._backdrop = null;
                    this._isShown = false;
                    this._isBodyOverflowing = false;
                    this._ignoreBackdropClick = false;
                    this._isTransitioning = false;
                    this._scrollbarWidth = 0;
                } // Getters


                var _proto = Modal.prototype;

                // Public
                _proto.toggle = function toggle(relatedTarget) {
                    return this._isShown ? this.hide() : this.show(relatedTarget);
                };

                _proto.show = function show(relatedTarget) {
                    var _this = this;

                    if (this._isShown || this._isTransitioning) {
                        return;
                    }

                    if ($(this._element).hasClass(ClassName$5.FADE)) {
                        this._isTransitioning = true;
                    }

                    var showEvent = $.Event(Event$5.SHOW, {
                        relatedTarget: relatedTarget
                    });
                    $(this._element).trigger(showEvent);

                    if (this._isShown || showEvent.isDefaultPrevented()) {
                        return;
                    }

                    this._isShown = true;

                    this._checkScrollbar();

                    this._setScrollbar();

                    this._adjustDialog();

                    this._setEscapeEvent();

                    this._setResizeEvent();

                    $(this._element).on(Event$5.CLICK_DISMISS, Selector$5.DATA_DISMISS, function (event) {
                        return _this.hide(event);
                    });
                    $(this._dialog).on(Event$5.MOUSEDOWN_DISMISS, function () {
                        $(_this._element).one(Event$5.MOUSEUP_DISMISS, function (event) {
                            if ($(event.target).is(_this._element)) {
                                _this._ignoreBackdropClick = true;
                            }
                        });
                    });

                    this._showBackdrop(function () {
                        return _this._showElement(relatedTarget);
                    });
                };

                _proto.hide = function hide(event) {
                    var _this2 = this;

                    if (event) {
                        event.preventDefault();
                    }

                    if (!this._isShown || this._isTransitioning) {
                        return;
                    }

                    var hideEvent = $.Event(Event$5.HIDE);
                    $(this._element).trigger(hideEvent);

                    if (!this._isShown || hideEvent.isDefaultPrevented()) {
                        return;
                    }

                    this._isShown = false;
                    var transition = $(this._element).hasClass(ClassName$5.FADE);

                    if (transition) {
                        this._isTransitioning = true;
                    }

                    this._setEscapeEvent();

                    this._setResizeEvent();

                    $(document).off(Event$5.FOCUSIN);
                    $(this._element).removeClass(ClassName$5.SHOW);
                    $(this._element).off(Event$5.CLICK_DISMISS);
                    $(this._dialog).off(Event$5.MOUSEDOWN_DISMISS);

                    if (transition) {
                        var transitionDuration = Util.getTransitionDurationFromElement(this._element);
                        $(this._element).one(Util.TRANSITION_END, function (event) {
                            return _this2._hideModal(event);
                        }).emulateTransitionEnd(transitionDuration);
                    } else {
                        this._hideModal();
                    }
                };

                _proto.dispose = function dispose() {
                    [window, this._element, this._dialog].forEach(function (htmlElement) {
                        return $(htmlElement).off(EVENT_KEY$5);
                    });
                    /**
                     * `document` has 2 events `Event.FOCUSIN` and `Event.CLICK_DATA_API`
                     * Do not move `document` in `htmlElements` array
                     * It will remove `Event.CLICK_DATA_API` event that should remain
                     */

                    $(document).off(Event$5.FOCUSIN);
                    $.removeData(this._element, DATA_KEY$5);
                    this._config = null;
                    this._element = null;
                    this._dialog = null;
                    this._backdrop = null;
                    this._isShown = null;
                    this._isBodyOverflowing = null;
                    this._ignoreBackdropClick = null;
                    this._isTransitioning = null;
                    this._scrollbarWidth = null;
                };

                _proto.handleUpdate = function handleUpdate() {
                    this._adjustDialog();
                } // Private
                ;

                _proto._getConfig = function _getConfig(config) {
                    config = _objectSpread2({}, Default$3, {}, config);
                    Util.typeCheckConfig(NAME$5, config, DefaultType$3);
                    return config;
                };

                _proto._triggerBackdropTransition = function _triggerBackdropTransition() {
                    var _this3 = this;

                    if (this._config.backdrop === 'static') {
                        var hideEventPrevented = $.Event(Event$5.HIDE_PREVENTED);
                        $(this._element).trigger(hideEventPrevented);

                        if (hideEventPrevented.defaultPrevented) {
                            return;
                        }

                        this._element.classList.add(ClassName$5.STATIC);

                        var modalTransitionDuration = Util.getTransitionDurationFromElement(this._element);
                        $(this._element).one(Util.TRANSITION_END, function () {
                            _this3._element.classList.remove(ClassName$5.STATIC);
                        }).emulateTransitionEnd(modalTransitionDuration);

                        this._element.focus();
                    } else {
                        this.hide();
                    }
                };

                _proto._showElement = function _showElement(relatedTarget) {
                    var _this4 = this;

                    var transition = $(this._element).hasClass(ClassName$5.FADE);
                    var modalBody = this._dialog ? this._dialog.querySelector(Selector$5.MODAL_BODY) : null;

                    if (!this._element.parentNode || this._element.parentNode.nodeType !== Node.ELEMENT_NODE) {
                        // Don't move modal's DOM position
                        document.body.appendChild(this._element);
                    }

                    this._element.style.display = 'block';

                    this._element.removeAttribute('aria-hidden');

                    this._element.setAttribute('aria-modal', true);

                    if ($(this._dialog).hasClass(ClassName$5.SCROLLABLE) && modalBody) {
                        modalBody.scrollTop = 0;
                    } else {
                        this._element.scrollTop = 0;
                    }

                    if (transition) {
                        Util.reflow(this._element);
                    }

                    $(this._element).addClass(ClassName$5.SHOW);

                    if (this._config.focus) {
                        this._enforceFocus();
                    }

                    var shownEvent = $.Event(Event$5.SHOWN, {
                        relatedTarget: relatedTarget
                    });

                    var transitionComplete = function transitionComplete() {
                        if (_this4._config.focus) {
                            _this4._element.focus();
                        }

                        _this4._isTransitioning = false;
                        $(_this4._element).trigger(shownEvent);
                    };

                    if (transition) {
                        var transitionDuration = Util.getTransitionDurationFromElement(this._dialog);
                        $(this._dialog).one(Util.TRANSITION_END, transitionComplete).emulateTransitionEnd(transitionDuration);
                    } else {
                        transitionComplete();
                    }
                };

                _proto._enforceFocus = function _enforceFocus() {
                    var _this5 = this;

                    $(document).off(Event$5.FOCUSIN) // Guard against infinite focus loop
                        .on(Event$5.FOCUSIN, function (event) {
                            if (document !== event.target && _this5._element !== event.target && $(_this5._element).has(event.target).length === 0) {
                                _this5._element.focus();
                            }
                        });
                };

                _proto._setEscapeEvent = function _setEscapeEvent() {
                    var _this6 = this;

                    if (this._isShown && this._config.keyboard) {
                        $(this._element).on(Event$5.KEYDOWN_DISMISS, function (event) {
                            if (event.which === ESCAPE_KEYCODE$1) {
                                _this6._triggerBackdropTransition();
                            }
                        });
                    } else if (!this._isShown) {
                        $(this._element).off(Event$5.KEYDOWN_DISMISS);
                    }
                };

                _proto._setResizeEvent = function _setResizeEvent() {
                    var _this7 = this;

                    if (this._isShown) {
                        $(window).on(Event$5.RESIZE, function (event) {
                            return _this7.handleUpdate(event);
                        });
                    } else {
                        $(window).off(Event$5.RESIZE);
                    }
                };

                _proto._hideModal = function _hideModal() {
                    var _this8 = this;

                    this._element.style.display = 'none';

                    this._element.setAttribute('aria-hidden', true);

                    this._element.removeAttribute('aria-modal');

                    this._isTransitioning = false;

                    this._showBackdrop(function () {
                        $(document.body).removeClass(ClassName$5.OPEN);

                        _this8._resetAdjustments();

                        _this8._resetScrollbar();

                        $(_this8._element).trigger(Event$5.HIDDEN);
                    });
                };

                _proto._removeBackdrop = function _removeBackdrop() {
                    if (this._backdrop) {
                        $(this._backdrop).remove();
                        this._backdrop = null;
                    }
                };

                _proto._showBackdrop = function _showBackdrop(callback) {
                    var _this9 = this;

                    var animate = $(this._element).hasClass(ClassName$5.FADE) ? ClassName$5.FADE : '';

                    if (this._isShown && this._config.backdrop) {
                        this._backdrop = document.createElement('div');
                        this._backdrop.className = ClassName$5.BACKDROP;

                        if (animate) {
                            this._backdrop.classList.add(animate);
                        }

                        $(this._backdrop).appendTo(document.body);
                        $(this._element).on(Event$5.CLICK_DISMISS, function (event) {
                            if (_this9._ignoreBackdropClick) {
                                _this9._ignoreBackdropClick = false;
                                return;
                            }

                            if (event.target !== event.currentTarget) {
                                return;
                            }

                            _this9._triggerBackdropTransition();
                        });

                        if (animate) {
                            Util.reflow(this._backdrop);
                        }

                        $(this._backdrop).addClass(ClassName$5.SHOW);

                        if (!callback) {
                            return;
                        }

                        if (!animate) {
                            callback();
                            return;
                        }

                        var backdropTransitionDuration = Util.getTransitionDurationFromElement(this._backdrop);
                        $(this._backdrop).one(Util.TRANSITION_END, callback).emulateTransitionEnd(backdropTransitionDuration);
                    } else if (!this._isShown && this._backdrop) {
                        $(this._backdrop).removeClass(ClassName$5.SHOW);

                        var callbackRemove = function callbackRemove() {
                            _this9._removeBackdrop();

                            if (callback) {
                                callback();
                            }
                        };

                        if ($(this._element).hasClass(ClassName$5.FADE)) {
                            var _backdropTransitionDuration = Util.getTransitionDurationFromElement(this._backdrop);

                            $(this._backdrop).one(Util.TRANSITION_END, callbackRemove).emulateTransitionEnd(_backdropTransitionDuration);
                        } else {
                            callbackRemove();
                        }
                    } else if (callback) {
                        callback();
                    }
                } // ----------------------------------------------------------------------
                // the following methods are used to handle overflowing modals
                // todo (fat): these should probably be refactored out of modal.js
                // ----------------------------------------------------------------------
                ;

                _proto._adjustDialog = function _adjustDialog() {
                    var isModalOverflowing = this._element.scrollHeight > document.documentElement.clientHeight;

                    if (!this._isBodyOverflowing && isModalOverflowing) {
                        this._element.style.paddingLeft = this._scrollbarWidth + "px";
                    }

                    if (this._isBodyOverflowing && !isModalOverflowing) {
                        this._element.style.paddingRight = this._scrollbarWidth + "px";
                    }
                };

                _proto._resetAdjustments = function _resetAdjustments() {
                    this._element.style.paddingLeft = '';
                    this._element.style.paddingRight = '';
                };

                _proto._checkScrollbar = function _checkScrollbar() {
                    var rect = document.body.getBoundingClientRect();
                    this._isBodyOverflowing = rect.left + rect.right < window.innerWidth;
                    this._scrollbarWidth = this._getScrollbarWidth();
                };

                _proto._setScrollbar = function _setScrollbar() {
                    var _this10 = this;

                    if (this._isBodyOverflowing) {
                        // Note: DOMNode.style.paddingRight returns the actual value or '' if not set
                        //   while $(DOMNode).css('padding-right') returns the calculated value or 0 if not set
                        var fixedContent = [].slice.call(document.querySelectorAll(Selector$5.FIXED_CONTENT));
                        var stickyContent = [].slice.call(document.querySelectorAll(Selector$5.STICKY_CONTENT)); // Adjust fixed content padding

                        $(fixedContent).each(function (index, element) {
                            var actualPadding = element.style.paddingRight;
                            var calculatedPadding = $(element).css('padding-right');
                            $(element).data('padding-right', actualPadding).css('padding-right', parseFloat(calculatedPadding) + _this10._scrollbarWidth + "px");
                        }); // Adjust sticky content margin

                        $(stickyContent).each(function (index, element) {
                            var actualMargin = element.style.marginRight;
                            var calculatedMargin = $(element).css('margin-right');
                            $(element).data('margin-right', actualMargin).css('margin-right', parseFloat(calculatedMargin) - _this10._scrollbarWidth + "px");
                        }); // Adjust body padding

                        var actualPadding = document.body.style.paddingRight;
                        var calculatedPadding = $(document.body).css('padding-right');
                        $(document.body).data('padding-right', actualPadding).css('padding-right', parseFloat(calculatedPadding) + this._scrollbarWidth + "px");
                    }

                    $(document.body).addClass(ClassName$5.OPEN);
                };

                _proto._resetScrollbar = function _resetScrollbar() {
                    // Restore fixed content padding
                    var fixedContent = [].slice.call(document.querySelectorAll(Selector$5.FIXED_CONTENT));
                    $(fixedContent).each(function (index, element) {
                        var padding = $(element).data('padding-right');
                        $(element).removeData('padding-right');
                        element.style.paddingRight = padding ? padding : '';
                    }); // Restore sticky content

                    var elements = [].slice.call(document.querySelectorAll("" + Selector$5.STICKY_CONTENT));
                    $(elements).each(function (index, element) {
                        var margin = $(element).data('margin-right');

                        if (typeof margin !== 'undefined') {
                            $(element).css('margin-right', margin).removeData('margin-right');
                        }
                    }); // Restore body padding

                    var padding = $(document.body).data('padding-right');
                    $(document.body).removeData('padding-right');
                    document.body.style.paddingRight = padding ? padding : '';
                };

                _proto._getScrollbarWidth = function _getScrollbarWidth() {
                    // thx d.walsh
                    var scrollDiv = document.createElement('div');
                    scrollDiv.className = ClassName$5.SCROLLBAR_MEASURER;
                    document.body.appendChild(scrollDiv);
                    var scrollbarWidth = scrollDiv.getBoundingClientRect().width - scrollDiv.clientWidth;
                    document.body.removeChild(scrollDiv);
                    return scrollbarWidth;
                } // Static
                ;

                Modal._jQueryInterface = function _jQueryInterface(config, relatedTarget) {
                    return this.each(function () {
                        var data = $(this).data(DATA_KEY$5);

                        var _config = _objectSpread2({}, Default$3, {}, $(this).data(), {}, typeof config === 'object' && config ? config : {});

                        if (!data) {
                            data = new Modal(this, _config);
                            $(this).data(DATA_KEY$5, data);
                        }

                        if (typeof config === 'string') {
                            if (typeof data[config] === 'undefined') {
                                throw new TypeError("No method named \"" + config + "\"");
                            }

                            data[config](relatedTarget);
                        } else if (_config.show) {
                            data.show(relatedTarget);
                        }
                    });
                };

                _createClass(Modal, null, [{
                    key: "VERSION",
                    get: function get() {
                        return VERSION$5;
                    }
                }, {
                    key: "Default",
                    get: function get() {
                        return Default$3;
                    }
                }]);

                return Modal;
            }();
        /**
         * ------------------------------------------------------------------------
         * Data Api implementation
         * ------------------------------------------------------------------------
         */


        $(document).on(Event$5.CLICK_DATA_API, Selector$5.DATA_TOGGLE, function (event) {
            var _this11 = this;

            var target;
            var selector = Util.getSelectorFromElement(this);

            if (selector) {
                target = document.querySelector(selector);
            }

            var config = $(target).data(DATA_KEY$5) ? 'toggle' : _objectSpread2({}, $(target).data(), {}, $(this).data());

            if (this.tagName === 'A' || this.tagName === 'AREA') {
                event.preventDefault();
            }

            var $target = $(target).one(Event$5.SHOW, function (showEvent) {
                if (showEvent.isDefaultPrevented()) {
                    // Only register focus restorer if modal will actually get shown
                    return;
                }

                $target.one(Event$5.HIDDEN, function () {
                    if ($(_this11).is(':visible')) {
                        _this11.focus();
                    }
                });
            });

            Modal._jQueryInterface.call($(target), config, this);
        });
        /**
         * ------------------------------------------------------------------------
         * jQuery
         * ------------------------------------------------------------------------
         */

        $.fn[NAME$5] = Modal._jQueryInterface;
        $.fn[NAME$5].Constructor = Modal;

        $.fn[NAME$5].noConflict = function () {
            $.fn[NAME$5] = JQUERY_NO_CONFLICT$5;
            return Modal._jQueryInterface;
        };

        /**
         * --------------------------------------------------------------------------
         * Bootstrap (v4.4.1): tools/sanitizer.js
         * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
         * --------------------------------------------------------------------------
         */
        var uriAttrs = ['background', 'cite', 'href', 'itemtype', 'longdesc', 'poster', 'src', 'xlink:href'];
        var ARIA_ATTRIBUTE_PATTERN = /^aria-[\w-]*$/i;
        var DefaultWhitelist = {
            // Global attributes allowed on any supplied element below.
            '*': ['class', 'dir', 'id', 'lang', 'role', ARIA_ATTRIBUTE_PATTERN],
            a: ['target', 'href', 'title', 'rel'],
            area: [],
            b: [],
            br: [],
            col: [],
            code: [],
            div: [],
            em: [],
            hr: [],
            h1: [],
            h2: [],
            h3: [],
            h4: [],
            h5: [],
            h6: [],
            i: [],
            img: ['src', 'alt', 'title', 'width', 'height'],
            li: [],
            ol: [],
            p: [],
            pre: [],
            s: [],
            small: [],
            span: [],
            sub: [],
            sup: [],
            strong: [],
            u: [],
            ul: []
        };
        /**
         * A pattern that recognizes a commonly useful subset of URLs that are safe.
         *
         * Shoutout to Angular 7 https://github.com/angular/angular/blob/7.2.4/packages/core/src/sanitization/url_sanitizer.ts
         */

        var SAFE_URL_PATTERN = /^(?:(?:https?|mailto|ftp|tel|file):|[^&:/?#]*(?:[/?#]|$))/gi;
        /**
         * A pattern that matches safe data URLs. Only matches image, video and audio types.
         *
         * Shoutout to Angular 7 https://github.com/angular/angular/blob/7.2.4/packages/core/src/sanitization/url_sanitizer.ts
         */

        var DATA_URL_PATTERN = /^data:(?:image\/(?:bmp|gif|jpeg|jpg|png|tiff|webp)|video\/(?:mpeg|mp4|ogg|webm)|audio\/(?:mp3|oga|ogg|opus));base64,[a-z0-9+/]+=*$/i;

        function allowedAttribute(attr, allowedAttributeList) {
            var attrName = attr.nodeName.toLowerCase();

            if (allowedAttributeList.indexOf(attrName) !== -1) {
                if (uriAttrs.indexOf(attrName) !== -1) {
                    return Boolean(attr.nodeValue.match(SAFE_URL_PATTERN) || attr.nodeValue.match(DATA_URL_PATTERN));
                }

                return true;
            }

            var regExp = allowedAttributeList.filter(function (attrRegex) {
                return attrRegex instanceof RegExp;
            }); // Check if a regular expression validates the attribute.

            for (var i = 0, l = regExp.length; i < l; i++) {
                if (attrName.match(regExp[i])) {
                    return true;
                }
            }

            return false;
        }

        function sanitizeHtml(unsafeHtml, whiteList, sanitizeFn) {
            if (unsafeHtml.length === 0) {
                return unsafeHtml;
            }

            if (sanitizeFn && typeof sanitizeFn === 'function') {
                return sanitizeFn(unsafeHtml);
            }

            var domParser = new window.DOMParser();
            var createdDocument = domParser.parseFromString(unsafeHtml, 'text/html');
            var whitelistKeys = Object.keys(whiteList);
            var elements = [].slice.call(createdDocument.body.querySelectorAll('*'));

            var _loop = function _loop(i, len) {
                var el = elements[i];
                var elName = el.nodeName.toLowerCase();

                if (whitelistKeys.indexOf(el.nodeName.toLowerCase()) === -1) {
                    el.parentNode.removeChild(el);
                    return "continue";
                }

                var attributeList = [].slice.call(el.attributes);
                var whitelistedAttributes = [].concat(whiteList['*'] || [], whiteList[elName] || []);
                attributeList.forEach(function (attr) {
                    if (!allowedAttribute(attr, whitelistedAttributes)) {
                        el.removeAttribute(attr.nodeName);
                    }
                });
            };

            for (var i = 0, len = elements.length; i < len; i++) {
                var _ret = _loop(i);

                if (_ret === "continue") continue;
            }

            return createdDocument.body.innerHTML;
        }

        /**
         * ------------------------------------------------------------------------
         * Constants
         * ------------------------------------------------------------------------
         */

        var NAME$6 = 'tooltip';
        var VERSION$6 = '4.4.1';
        var DATA_KEY$6 = 'bs.tooltip';
        var EVENT_KEY$6 = "." + DATA_KEY$6;
        var JQUERY_NO_CONFLICT$6 = $.fn[NAME$6];
        var CLASS_PREFIX = 'bs-tooltip';
        var BSCLS_PREFIX_REGEX = new RegExp("(^|\\s)" + CLASS_PREFIX + "\\S+", 'g');
        var DISALLOWED_ATTRIBUTES = ['sanitize', 'whiteList', 'sanitizeFn'];
        var DefaultType$4 = {
            animation: 'boolean',
            template: 'string',
            title: '(string|element|function)',
            trigger: 'string',
            delay: '(number|object)',
            html: 'boolean',
            selector: '(string|boolean)',
            placement: '(string|function)',
            offset: '(number|string|function)',
            container: '(string|element|boolean)',
            fallbackPlacement: '(string|array)',
            boundary: '(string|element)',
            sanitize: 'boolean',
            sanitizeFn: '(null|function)',
            whiteList: 'object',
            popperConfig: '(null|object)'
        };
        var AttachmentMap$1 = {
            AUTO: 'auto',
            TOP: 'top',
            RIGHT: 'right',
            BOTTOM: 'bottom',
            LEFT: 'left'
        };
        var Default$4 = {
            animation: true,
            template: '<div class="tooltip" role="tooltip">' + '<div class="arrow"></div>' + '<div class="tooltip-inner"></div></div>',
            trigger: 'hover focus',
            title: '',
            delay: 0,
            html: false,
            selector: false,
            placement: 'top',
            offset: 0,
            container: false,
            fallbackPlacement: 'flip',
            boundary: 'scrollParent',
            sanitize: true,
            sanitizeFn: null,
            whiteList: DefaultWhitelist,
            popperConfig: null
        };
        var HoverState = {
            SHOW: 'show',
            OUT: 'out'
        };
        var Event$6 = {
            HIDE: "hide" + EVENT_KEY$6,
            HIDDEN: "hidden" + EVENT_KEY$6,
            SHOW: "show" + EVENT_KEY$6,
            SHOWN: "shown" + EVENT_KEY$6,
            INSERTED: "inserted" + EVENT_KEY$6,
            CLICK: "click" + EVENT_KEY$6,
            FOCUSIN: "focusin" + EVENT_KEY$6,
            FOCUSOUT: "focusout" + EVENT_KEY$6,
            MOUSEENTER: "mouseenter" + EVENT_KEY$6,
            MOUSELEAVE: "mouseleave" + EVENT_KEY$6
        };
        var ClassName$6 = {
            FADE: 'fade',
            SHOW: 'show'
        };
        var Selector$6 = {
            TOOLTIP: '.tooltip',
            TOOLTIP_INNER: '.tooltip-inner',
            ARROW: '.arrow'
        };
        var Trigger = {
            HOVER: 'hover',
            FOCUS: 'focus',
            CLICK: 'click',
            MANUAL: 'manual'
        };
        /**
         * ------------------------------------------------------------------------
         * Class Definition
         * ------------------------------------------------------------------------
         */

        var Tooltip =
            /*#__PURE__*/
            function () {
                function Tooltip(element, config) {
                    if (typeof Popper === 'undefined') {
                        throw new TypeError('Bootstrap\'s tooltips require Popper.js (https://popper.js.org/)');
                    } // private


                    this._isEnabled = true;
                    this._timeout = 0;
                    this._hoverState = '';
                    this._activeTrigger = {};
                    this._popper = null; // Protected

                    this.element = element;
                    this.config = this._getConfig(config);
                    this.tip = null;

                    this._setListeners();
                } // Getters


                var _proto = Tooltip.prototype;

                // Public
                _proto.enable = function enable() {
                    this._isEnabled = true;
                };

                _proto.disable = function disable() {
                    this._isEnabled = false;
                };

                _proto.toggleEnabled = function toggleEnabled() {
                    this._isEnabled = !this._isEnabled;
                };

                _proto.toggle = function toggle(event) {
                    if (!this._isEnabled) {
                        return;
                    }

                    if (event) {
                        var dataKey = this.constructor.DATA_KEY;
                        var context = $(event.currentTarget).data(dataKey);

                        if (!context) {
                            context = new this.constructor(event.currentTarget, this._getDelegateConfig());
                            $(event.currentTarget).data(dataKey, context);
                        }

                        context._activeTrigger.click = !context._activeTrigger.click;

                        if (context._isWithActiveTrigger()) {
                            context._enter(null, context);
                        } else {
                            context._leave(null, context);
                        }
                    } else {
                        if ($(this.getTipElement()).hasClass(ClassName$6.SHOW)) {
                            this._leave(null, this);

                            return;
                        }

                        this._enter(null, this);
                    }
                };

                _proto.dispose = function dispose() {
                    clearTimeout(this._timeout);
                    $.removeData(this.element, this.constructor.DATA_KEY);
                    $(this.element).off(this.constructor.EVENT_KEY);
                    $(this.element).closest('.modal').off('hide.bs.modal', this._hideModalHandler);

                    if (this.tip) {
                        $(this.tip).remove();
                    }

                    this._isEnabled = null;
                    this._timeout = null;
                    this._hoverState = null;
                    this._activeTrigger = null;

                    if (this._popper) {
                        this._popper.destroy();
                    }

                    this._popper = null;
                    this.element = null;
                    this.config = null;
                    this.tip = null;
                };

                _proto.show = function show() {
                    var _this = this;

                    if ($(this.element).css('display') === 'none') {
                        throw new Error('Please use show on visible elements');
                    }

                    var showEvent = $.Event(this.constructor.Event.SHOW);

                    if (this.isWithContent() && this._isEnabled) {
                        $(this.element).trigger(showEvent);
                        var shadowRoot = Util.findShadowRoot(this.element);
                        var isInTheDom = $.contains(shadowRoot !== null ? shadowRoot : this.element.ownerDocument.documentElement, this.element);

                        if (showEvent.isDefaultPrevented() || !isInTheDom) {
                            return;
                        }

                        var tip = this.getTipElement();
                        var tipId = Util.getUID(this.constructor.NAME);
                        tip.setAttribute('id', tipId);
                        this.element.setAttribute('aria-describedby', tipId);
                        this.setContent();

                        if (this.config.animation) {
                            $(tip).addClass(ClassName$6.FADE);
                        }

                        var placement = typeof this.config.placement === 'function' ? this.config.placement.call(this, tip, this.element) : this.config.placement;

                        var attachment = this._getAttachment(placement);

                        this.addAttachmentClass(attachment);

                        var container = this._getContainer();

                        $(tip).data(this.constructor.DATA_KEY, this);

                        if (!$.contains(this.element.ownerDocument.documentElement, this.tip)) {
                            $(tip).appendTo(container);
                        }

                        $(this.element).trigger(this.constructor.Event.INSERTED);
                        this._popper = new Popper(this.element, tip, this._getPopperConfig(attachment));
                        $(tip).addClass(ClassName$6.SHOW); // If this is a touch-enabled device we add extra
                        // empty mouseover listeners to the body's immediate children;
                        // only needed because of broken event delegation on iOS
                        // https://www.quirksmode.org/blog/archives/2014/02/mouse_event_bub.html

                        if ('ontouchstart' in document.documentElement) {
                            $(document.body).children().on('mouseover', null, $.noop);
                        }

                        var complete = function complete() {
                            if (_this.config.animation) {
                                _this._fixTransition();
                            }

                            var prevHoverState = _this._hoverState;
                            _this._hoverState = null;
                            $(_this.element).trigger(_this.constructor.Event.SHOWN);

                            if (prevHoverState === HoverState.OUT) {
                                _this._leave(null, _this);
                            }
                        };

                        if ($(this.tip).hasClass(ClassName$6.FADE)) {
                            var transitionDuration = Util.getTransitionDurationFromElement(this.tip);
                            $(this.tip).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
                        } else {
                            complete();
                        }
                    }
                };

                _proto.hide = function hide(callback) {
                    var _this2 = this;

                    var tip = this.getTipElement();
                    var hideEvent = $.Event(this.constructor.Event.HIDE);

                    var complete = function complete() {
                        if (_this2._hoverState !== HoverState.SHOW && tip.parentNode) {
                            tip.parentNode.removeChild(tip);
                        }

                        _this2._cleanTipClass();

                        _this2.element.removeAttribute('aria-describedby');

                        $(_this2.element).trigger(_this2.constructor.Event.HIDDEN);

                        if (_this2._popper !== null) {
                            _this2._popper.destroy();
                        }

                        if (callback) {
                            callback();
                        }
                    };

                    $(this.element).trigger(hideEvent);

                    if (hideEvent.isDefaultPrevented()) {
                        return;
                    }

                    $(tip).removeClass(ClassName$6.SHOW); // If this is a touch-enabled device we remove the extra
                    // empty mouseover listeners we added for iOS support

                    if ('ontouchstart' in document.documentElement) {
                        $(document.body).children().off('mouseover', null, $.noop);
                    }

                    this._activeTrigger[Trigger.CLICK] = false;
                    this._activeTrigger[Trigger.FOCUS] = false;
                    this._activeTrigger[Trigger.HOVER] = false;

                    if ($(this.tip).hasClass(ClassName$6.FADE)) {
                        var transitionDuration = Util.getTransitionDurationFromElement(tip);
                        $(tip).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
                    } else {
                        complete();
                    }

                    this._hoverState = '';
                };

                _proto.update = function update() {
                    if (this._popper !== null) {
                        this._popper.scheduleUpdate();
                    }
                } // Protected
                ;

                _proto.isWithContent = function isWithContent() {
                    return Boolean(this.getTitle());
                };

                _proto.addAttachmentClass = function addAttachmentClass(attachment) {
                    $(this.getTipElement()).addClass(CLASS_PREFIX + "-" + attachment);
                };

                _proto.getTipElement = function getTipElement() {
                    this.tip = this.tip || $(this.config.template)[0];
                    return this.tip;
                };

                _proto.setContent = function setContent() {
                    var tip = this.getTipElement();
                    this.setElementContent($(tip.querySelectorAll(Selector$6.TOOLTIP_INNER)), this.getTitle());
                    $(tip).removeClass(ClassName$6.FADE + " " + ClassName$6.SHOW);
                };

                _proto.setElementContent = function setElementContent($element, content) {
                    if (typeof content === 'object' && (content.nodeType || content.jquery)) {
                        // Content is a DOM node or a jQuery
                        if (this.config.html) {
                            if (!$(content).parent().is($element)) {
                                $element.empty().append(content);
                            }
                        } else {
                            $element.text($(content).text());
                        }

                        return;
                    }

                    if (this.config.html) {
                        if (this.config.sanitize) {
                            content = sanitizeHtml(content, this.config.whiteList, this.config.sanitizeFn);
                        }

                        $element.html(content);
                    } else {
                        $element.text(content);
                    }
                };

                _proto.getTitle = function getTitle() {
                    var title = this.element.getAttribute('data-original-title');

                    if (!title) {
                        title = typeof this.config.title === 'function' ? this.config.title.call(this.element) : this.config.title;
                    }

                    return title;
                } // Private
                ;

                _proto._getPopperConfig = function _getPopperConfig(attachment) {
                    var _this3 = this;

                    var defaultBsConfig = {
                        placement: attachment,
                        modifiers: {
                            offset: this._getOffset(),
                            flip: {
                                behavior: this.config.fallbackPlacement
                            },
                            arrow: {
                                element: Selector$6.ARROW
                            },
                            preventOverflow: {
                                boundariesElement: this.config.boundary
                            }
                        },
                        onCreate: function onCreate(data) {
                            if (data.originalPlacement !== data.placement) {
                                _this3._handlePopperPlacementChange(data);
                            }
                        },
                        onUpdate: function onUpdate(data) {
                            return _this3._handlePopperPlacementChange(data);
                        }
                    };
                    return _objectSpread2({}, defaultBsConfig, {}, this.config.popperConfig);
                };

                _proto._getOffset = function _getOffset() {
                    var _this4 = this;

                    var offset = {};

                    if (typeof this.config.offset === 'function') {
                        offset.fn = function (data) {
                            data.offsets = _objectSpread2({}, data.offsets, {}, _this4.config.offset(data.offsets, _this4.element) || {});
                            return data;
                        };
                    } else {
                        offset.offset = this.config.offset;
                    }

                    return offset;
                };

                _proto._getContainer = function _getContainer() {
                    if (this.config.container === false) {
                        return document.body;
                    }

                    if (Util.isElement(this.config.container)) {
                        return $(this.config.container);
                    }

                    return $(document).find(this.config.container);
                };

                _proto._getAttachment = function _getAttachment(placement) {
                    return AttachmentMap$1[placement.toUpperCase()];
                };

                _proto._setListeners = function _setListeners() {
                    var _this5 = this;

                    var triggers = this.config.trigger.split(' ');
                    triggers.forEach(function (trigger) {
                        if (trigger === 'click') {
                            $(_this5.element).on(_this5.constructor.Event.CLICK, _this5.config.selector, function (event) {
                                return _this5.toggle(event);
                            });
                        } else if (trigger !== Trigger.MANUAL) {
                            var eventIn = trigger === Trigger.HOVER ? _this5.constructor.Event.MOUSEENTER : _this5.constructor.Event.FOCUSIN;
                            var eventOut = trigger === Trigger.HOVER ? _this5.constructor.Event.MOUSELEAVE : _this5.constructor.Event.FOCUSOUT;
                            $(_this5.element).on(eventIn, _this5.config.selector, function (event) {
                                return _this5._enter(event);
                            }).on(eventOut, _this5.config.selector, function (event) {
                                return _this5._leave(event);
                            });
                        }
                    });

                    this._hideModalHandler = function () {
                        if (_this5.element) {
                            _this5.hide();
                        }
                    };

                    $(this.element).closest('.modal').on('hide.bs.modal', this._hideModalHandler);

                    if (this.config.selector) {
                        this.config = _objectSpread2({}, this.config, {
                            trigger: 'manual',
                            selector: ''
                        });
                    } else {
                        this._fixTitle();
                    }
                };

                _proto._fixTitle = function _fixTitle() {
                    var titleType = typeof this.element.getAttribute('data-original-title');

                    if (this.element.getAttribute('title') || titleType !== 'string') {
                        this.element.setAttribute('data-original-title', this.element.getAttribute('title') || '');
                        this.element.setAttribute('title', '');
                    }
                };

                _proto._enter = function _enter(event, context) {
                    var dataKey = this.constructor.DATA_KEY;
                    context = context || $(event.currentTarget).data(dataKey);

                    if (!context) {
                        context = new this.constructor(event.currentTarget, this._getDelegateConfig());
                        $(event.currentTarget).data(dataKey, context);
                    }

                    if (event) {
                        context._activeTrigger[event.type === 'focusin' ? Trigger.FOCUS : Trigger.HOVER] = true;
                    }

                    if ($(context.getTipElement()).hasClass(ClassName$6.SHOW) || context._hoverState === HoverState.SHOW) {
                        context._hoverState = HoverState.SHOW;
                        return;
                    }

                    clearTimeout(context._timeout);
                    context._hoverState = HoverState.SHOW;

                    if (!context.config.delay || !context.config.delay.show) {
                        context.show();
                        return;
                    }

                    context._timeout = setTimeout(function () {
                        if (context._hoverState === HoverState.SHOW) {
                            context.show();
                        }
                    }, context.config.delay.show);
                };

                _proto._leave = function _leave(event, context) {
                    var dataKey = this.constructor.DATA_KEY;
                    context = context || $(event.currentTarget).data(dataKey);

                    if (!context) {
                        context = new this.constructor(event.currentTarget, this._getDelegateConfig());
                        $(event.currentTarget).data(dataKey, context);
                    }

                    if (event) {
                        context._activeTrigger[event.type === 'focusout' ? Trigger.FOCUS : Trigger.HOVER] = false;
                    }

                    if (context._isWithActiveTrigger()) {
                        return;
                    }

                    clearTimeout(context._timeout);
                    context._hoverState = HoverState.OUT;

                    if (!context.config.delay || !context.config.delay.hide) {
                        context.hide();
                        return;
                    }

                    context._timeout = setTimeout(function () {
                        if (context._hoverState === HoverState.OUT) {
                            context.hide();
                        }
                    }, context.config.delay.hide);
                };

                _proto._isWithActiveTrigger = function _isWithActiveTrigger() {
                    for (var trigger in this._activeTrigger) {
                        if (this._activeTrigger[trigger]) {
                            return true;
                        }
                    }

                    return false;
                };

                _proto._getConfig = function _getConfig(config) {
                    var dataAttributes = $(this.element).data();
                    Object.keys(dataAttributes).forEach(function (dataAttr) {
                        if (DISALLOWED_ATTRIBUTES.indexOf(dataAttr) !== -1) {
                            delete dataAttributes[dataAttr];
                        }
                    });
                    config = _objectSpread2({}, this.constructor.Default, {}, dataAttributes, {}, typeof config === 'object' && config ? config : {});

                    if (typeof config.delay === 'number') {
                        config.delay = {
                            show: config.delay,
                            hide: config.delay
                        };
                    }

                    if (typeof config.title === 'number') {
                        config.title = config.title.toString();
                    }

                    if (typeof config.content === 'number') {
                        config.content = config.content.toString();
                    }

                    Util.typeCheckConfig(NAME$6, config, this.constructor.DefaultType);

                    if (config.sanitize) {
                        config.template = sanitizeHtml(config.template, config.whiteList, config.sanitizeFn);
                    }

                    return config;
                };

                _proto._getDelegateConfig = function _getDelegateConfig() {
                    var config = {};

                    if (this.config) {
                        for (var key in this.config) {
                            if (this.constructor.Default[key] !== this.config[key]) {
                                config[key] = this.config[key];
                            }
                        }
                    }

                    return config;
                };

                _proto._cleanTipClass = function _cleanTipClass() {
                    var $tip = $(this.getTipElement());
                    var tabClass = $tip.attr('class').match(BSCLS_PREFIX_REGEX);

                    if (tabClass !== null && tabClass.length) {
                        $tip.removeClass(tabClass.join(''));
                    }
                };

                _proto._handlePopperPlacementChange = function _handlePopperPlacementChange(popperData) {
                    var popperInstance = popperData.instance;
                    this.tip = popperInstance.popper;

                    this._cleanTipClass();

                    this.addAttachmentClass(this._getAttachment(popperData.placement));
                };

                _proto._fixTransition = function _fixTransition() {
                    var tip = this.getTipElement();
                    var initConfigAnimation = this.config.animation;

                    if (tip.getAttribute('x-placement') !== null) {
                        return;
                    }

                    $(tip).removeClass(ClassName$6.FADE);
                    this.config.animation = false;
                    this.hide();
                    this.show();
                    this.config.animation = initConfigAnimation;
                } // Static
                ;

                Tooltip._jQueryInterface = function _jQueryInterface(config) {
                    return this.each(function () {
                        var data = $(this).data(DATA_KEY$6);

                        var _config = typeof config === 'object' && config;

                        if (!data && /dispose|hide/.test(config)) {
                            return;
                        }

                        if (!data) {
                            data = new Tooltip(this, _config);
                            $(this).data(DATA_KEY$6, data);
                        }

                        if (typeof config === 'string') {
                            if (typeof data[config] === 'undefined') {
                                throw new TypeError("No method named \"" + config + "\"");
                            }

                            data[config]();
                        }
                    });
                };

                _createClass(Tooltip, null, [{
                    key: "VERSION",
                    get: function get() {
                        return VERSION$6;
                    }
                }, {
                    key: "Default",
                    get: function get() {
                        return Default$4;
                    }
                }, {
                    key: "NAME",
                    get: function get() {
                        return NAME$6;
                    }
                }, {
                    key: "DATA_KEY",
                    get: function get() {
                        return DATA_KEY$6;
                    }
                }, {
                    key: "Event",
                    get: function get() {
                        return Event$6;
                    }
                }, {
                    key: "EVENT_KEY",
                    get: function get() {
                        return EVENT_KEY$6;
                    }
                }, {
                    key: "DefaultType",
                    get: function get() {
                        return DefaultType$4;
                    }
                }]);

                return Tooltip;
            }();
        /**
         * ------------------------------------------------------------------------
         * jQuery
         * ------------------------------------------------------------------------
         */


        $.fn[NAME$6] = Tooltip._jQueryInterface;
        $.fn[NAME$6].Constructor = Tooltip;

        $.fn[NAME$6].noConflict = function () {
            $.fn[NAME$6] = JQUERY_NO_CONFLICT$6;
            return Tooltip._jQueryInterface;
        };

        /**
         * ------------------------------------------------------------------------
         * Constants
         * ------------------------------------------------------------------------
         */

        var NAME$7 = 'popover';
        var VERSION$7 = '4.4.1';
        var DATA_KEY$7 = 'bs.popover';
        var EVENT_KEY$7 = "." + DATA_KEY$7;
        var JQUERY_NO_CONFLICT$7 = $.fn[NAME$7];
        var CLASS_PREFIX$1 = 'bs-popover';
        var BSCLS_PREFIX_REGEX$1 = new RegExp("(^|\\s)" + CLASS_PREFIX$1 + "\\S+", 'g');

        var Default$5 = _objectSpread2({}, Tooltip.Default, {
            placement: 'right',
            trigger: 'click',
            content: '',
            template: '<div class="popover" role="tooltip">' + '<div class="arrow"></div>' + '<h3 class="popover-header"></h3>' + '<div class="popover-body"></div></div>'
        });

        var DefaultType$5 = _objectSpread2({}, Tooltip.DefaultType, {
            content: '(string|element|function)'
        });

        var ClassName$7 = {
            FADE: 'fade',
            SHOW: 'show'
        };
        var Selector$7 = {
            TITLE: '.popover-header',
            CONTENT: '.popover-body'
        };
        var Event$7 = {
            HIDE: "hide" + EVENT_KEY$7,
            HIDDEN: "hidden" + EVENT_KEY$7,
            SHOW: "show" + EVENT_KEY$7,
            SHOWN: "shown" + EVENT_KEY$7,
            INSERTED: "inserted" + EVENT_KEY$7,
            CLICK: "click" + EVENT_KEY$7,
            FOCUSIN: "focusin" + EVENT_KEY$7,
            FOCUSOUT: "focusout" + EVENT_KEY$7,
            MOUSEENTER: "mouseenter" + EVENT_KEY$7,
            MOUSELEAVE: "mouseleave" + EVENT_KEY$7
        };
        /**
         * ------------------------------------------------------------------------
         * Class Definition
         * ------------------------------------------------------------------------
         */

        var Popover =
            /*#__PURE__*/
            function (_Tooltip) {
                _inheritsLoose(Popover, _Tooltip);

                function Popover() {
                    return _Tooltip.apply(this, arguments) || this;
                }

                var _proto = Popover.prototype;

                // Overrides
                _proto.isWithContent = function isWithContent() {
                    return this.getTitle() || this._getContent();
                };

                _proto.addAttachmentClass = function addAttachmentClass(attachment) {
                    $(this.getTipElement()).addClass(CLASS_PREFIX$1 + "-" + attachment);
                };

                _proto.getTipElement = function getTipElement() {
                    this.tip = this.tip || $(this.config.template)[0];
                    return this.tip;
                };

                _proto.setContent = function setContent() {
                    var $tip = $(this.getTipElement()); // We use append for html objects to maintain js events

                    this.setElementContent($tip.find(Selector$7.TITLE), this.getTitle());

                    var content = this._getContent();

                    if (typeof content === 'function') {
                        content = content.call(this.element);
                    }

                    this.setElementContent($tip.find(Selector$7.CONTENT), content);
                    $tip.removeClass(ClassName$7.FADE + " " + ClassName$7.SHOW);
                } // Private
                ;

                _proto._getContent = function _getContent() {
                    return this.element.getAttribute('data-content') || this.config.content;
                };

                _proto._cleanTipClass = function _cleanTipClass() {
                    var $tip = $(this.getTipElement());
                    var tabClass = $tip.attr('class').match(BSCLS_PREFIX_REGEX$1);

                    if (tabClass !== null && tabClass.length > 0) {
                        $tip.removeClass(tabClass.join(''));
                    }
                } // Static
                ;

                Popover._jQueryInterface = function _jQueryInterface(config) {
                    return this.each(function () {
                        var data = $(this).data(DATA_KEY$7);

                        var _config = typeof config === 'object' ? config : null;

                        if (!data && /dispose|hide/.test(config)) {
                            return;
                        }

                        if (!data) {
                            data = new Popover(this, _config);
                            $(this).data(DATA_KEY$7, data);
                        }

                        if (typeof config === 'string') {
                            if (typeof data[config] === 'undefined') {
                                throw new TypeError("No method named \"" + config + "\"");
                            }

                            data[config]();
                        }
                    });
                };

                _createClass(Popover, null, [{
                    key: "VERSION",
                    // Getters
                    get: function get() {
                        return VERSION$7;
                    }
                }, {
                    key: "Default",
                    get: function get() {
                        return Default$5;
                    }
                }, {
                    key: "NAME",
                    get: function get() {
                        return NAME$7;
                    }
                }, {
                    key: "DATA_KEY",
                    get: function get() {
                        return DATA_KEY$7;
                    }
                }, {
                    key: "Event",
                    get: function get() {
                        return Event$7;
                    }
                }, {
                    key: "EVENT_KEY",
                    get: function get() {
                        return EVENT_KEY$7;
                    }
                }, {
                    key: "DefaultType",
                    get: function get() {
                        return DefaultType$5;
                    }
                }]);

                return Popover;
            }(Tooltip);
        /**
         * ------------------------------------------------------------------------
         * jQuery
         * ------------------------------------------------------------------------
         */


        $.fn[NAME$7] = Popover._jQueryInterface;
        $.fn[NAME$7].Constructor = Popover;

        $.fn[NAME$7].noConflict = function () {
            $.fn[NAME$7] = JQUERY_NO_CONFLICT$7;
            return Popover._jQueryInterface;
        };

        /**
         * ------------------------------------------------------------------------
         * Constants
         * ------------------------------------------------------------------------
         */

        var NAME$8 = 'scrollspy';
        var VERSION$8 = '4.4.1';
        var DATA_KEY$8 = 'bs.scrollspy';
        var EVENT_KEY$8 = "." + DATA_KEY$8;
        var DATA_API_KEY$6 = '.data-api';
        var JQUERY_NO_CONFLICT$8 = $.fn[NAME$8];
        var Default$6 = {
            offset: 10,
            method: 'auto',
            target: ''
        };
        var DefaultType$6 = {
            offset: 'number',
            method: 'string',
            target: '(string|element)'
        };
        var Event$8 = {
            ACTIVATE: "activate" + EVENT_KEY$8,
            SCROLL: "scroll" + EVENT_KEY$8,
            LOAD_DATA_API: "load" + EVENT_KEY$8 + DATA_API_KEY$6
        };
        var ClassName$8 = {
            DROPDOWN_ITEM: 'nm_dropdown-item',
            DROPDOWN_MENU: 'nm_dropdown-menu',
            ACTIVE: 'active'
        };
        var Selector$8 = {
            DATA_SPY: '[data-spy="scroll"]',
            ACTIVE: '.active',
            NAV_LIST_GROUP: '.nav, .list-group',
            NAV_LINKS: '.nav-link',
            NAV_ITEMS: '.nav-item',
            LIST_ITEMS: '.list-group-item',
            DROPDOWN: '.nm_dropdown',
            DROPDOWN_ITEMS: '.nm_dropdown-item',
            DROPDOWN_TOGGLE: '.nm_dropdown-toggle'
        };
        var OffsetMethod = {
            OFFSET: 'offset',
            POSITION: 'position'
        };
        /**
         * ------------------------------------------------------------------------
         * Class Definition
         * ------------------------------------------------------------------------
         */

        var ScrollSpy =
            /*#__PURE__*/
            function () {
                function ScrollSpy(element, config) {
                    var _this = this;

                    this._element = element;
                    this._scrollElement = element.tagName === 'BODY' ? window : element;
                    this._config = this._getConfig(config);
                    this._selector = this._config.target + " " + Selector$8.NAV_LINKS + "," + (this._config.target + " " + Selector$8.LIST_ITEMS + ",") + (this._config.target + " " + Selector$8.DROPDOWN_ITEMS);
                    this._offsets = [];
                    this._targets = [];
                    this._activeTarget = null;
                    this._scrollHeight = 0;
                    $(this._scrollElement).on(Event$8.SCROLL, function (event) {
                        return _this._process(event);
                    });
                    this.refresh();

                    this._process();
                } // Getters


                var _proto = ScrollSpy.prototype;

                // Public
                _proto.refresh = function refresh() {
                    var _this2 = this;

                    var autoMethod = this._scrollElement === this._scrollElement.window ? OffsetMethod.OFFSET : OffsetMethod.POSITION;
                    var offsetMethod = this._config.method === 'auto' ? autoMethod : this._config.method;
                    var offsetBase = offsetMethod === OffsetMethod.POSITION ? this._getScrollTop() : 0;
                    this._offsets = [];
                    this._targets = [];
                    this._scrollHeight = this._getScrollHeight();
                    var targets = [].slice.call(document.querySelectorAll(this._selector));
                    targets.map(function (element) {
                        var target;
                        var targetSelector = Util.getSelectorFromElement(element);

                        if (targetSelector) {
                            target = document.querySelector(targetSelector);
                        }

                        if (target) {
                            var targetBCR = target.getBoundingClientRect();

                            if (targetBCR.width || targetBCR.height) {
                                // TODO (fat): remove sketch reliance on jQuery position/offset
                                return [$(target)[offsetMethod]().top + offsetBase, targetSelector];
                            }
                        }

                        return null;
                    }).filter(function (item) {
                        return item;
                    }).sort(function (a, b) {
                        return a[0] - b[0];
                    }).forEach(function (item) {
                        _this2._offsets.push(item[0]);

                        _this2._targets.push(item[1]);
                    });
                };

                _proto.dispose = function dispose() {
                    $.removeData(this._element, DATA_KEY$8);
                    $(this._scrollElement).off(EVENT_KEY$8);
                    this._element = null;
                    this._scrollElement = null;
                    this._config = null;
                    this._selector = null;
                    this._offsets = null;
                    this._targets = null;
                    this._activeTarget = null;
                    this._scrollHeight = null;
                } // Private
                ;

                _proto._getConfig = function _getConfig(config) {
                    config = _objectSpread2({}, Default$6, {}, typeof config === 'object' && config ? config : {});

                    if (typeof config.target !== 'string') {
                        var id = $(config.target).attr('id');

                        if (!id) {
                            id = Util.getUID(NAME$8);
                            $(config.target).attr('id', id);
                        }

                        config.target = "#" + id;
                    }

                    Util.typeCheckConfig(NAME$8, config, DefaultType$6);
                    return config;
                };

                _proto._getScrollTop = function _getScrollTop() {
                    return this._scrollElement === window ? this._scrollElement.pageYOffset : this._scrollElement.scrollTop;
                };

                _proto._getScrollHeight = function _getScrollHeight() {
                    return this._scrollElement.scrollHeight || Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
                };

                _proto._getOffsetHeight = function _getOffsetHeight() {
                    return this._scrollElement === window ? window.innerHeight : this._scrollElement.getBoundingClientRect().height;
                };

                _proto._process = function _process() {
                    var scrollTop = this._getScrollTop() + this._config.offset;

                    var scrollHeight = this._getScrollHeight();

                    var maxScroll = this._config.offset + scrollHeight - this._getOffsetHeight();

                    if (this._scrollHeight !== scrollHeight) {
                        this.refresh();
                    }

                    if (scrollTop >= maxScroll) {
                        var target = this._targets[this._targets.length - 1];

                        if (this._activeTarget !== target) {
                            this._activate(target);
                        }

                        return;
                    }

                    if (this._activeTarget && scrollTop < this._offsets[0] && this._offsets[0] > 0) {
                        this._activeTarget = null;

                        this._clear();

                        return;
                    }

                    var offsetLength = this._offsets.length;

                    for (var i = offsetLength; i--;) {
                        var isActiveTarget = this._activeTarget !== this._targets[i] && scrollTop >= this._offsets[i] && (typeof this._offsets[i + 1] === 'undefined' || scrollTop < this._offsets[i + 1]);

                        if (isActiveTarget) {
                            this._activate(this._targets[i]);
                        }
                    }
                };

                _proto._activate = function _activate(target) {
                    this._activeTarget = target;

                    this._clear();

                    var queries = this._selector.split(',').map(function (selector) {
                        return selector + "[data-target=\"" + target + "\"]," + selector + "[href=\"" + target + "\"]";
                    });

                    var $link = $([].slice.call(document.querySelectorAll(queries.join(','))));

                    if ($link.hasClass(ClassName$8.DROPDOWN_ITEM)) {
                        $link.closest(Selector$8.DROPDOWN).find(Selector$8.DROPDOWN_TOGGLE).addClass(ClassName$8.ACTIVE);
                        $link.addClass(ClassName$8.ACTIVE);
                    } else {
                        // Set triggered link as active
                        $link.addClass(ClassName$8.ACTIVE); // Set triggered links parents as active
                        // With both <ul> and <nav> markup a parent is the previous sibling of any nav ancestor

                        $link.parents(Selector$8.NAV_LIST_GROUP).prev(Selector$8.NAV_LINKS + ", " + Selector$8.LIST_ITEMS).addClass(ClassName$8.ACTIVE); // Handle special case when .nav-link is inside .nav-item

                        $link.parents(Selector$8.NAV_LIST_GROUP).prev(Selector$8.NAV_ITEMS).children(Selector$8.NAV_LINKS).addClass(ClassName$8.ACTIVE);
                    }

                    $(this._scrollElement).trigger(Event$8.ACTIVATE, {
                        relatedTarget: target
                    });
                };

                _proto._clear = function _clear() {
                    [].slice.call(document.querySelectorAll(this._selector)).filter(function (node) {
                        return node.classList.contains(ClassName$8.ACTIVE);
                    }).forEach(function (node) {
                        return node.classList.remove(ClassName$8.ACTIVE);
                    });
                } // Static
                ;

                ScrollSpy._jQueryInterface = function _jQueryInterface(config) {
                    return this.each(function () {
                        var data = $(this).data(DATA_KEY$8);

                        var _config = typeof config === 'object' && config;

                        if (!data) {
                            data = new ScrollSpy(this, _config);
                            $(this).data(DATA_KEY$8, data);
                        }

                        if (typeof config === 'string') {
                            if (typeof data[config] === 'undefined') {
                                throw new TypeError("No method named \"" + config + "\"");
                            }

                            data[config]();
                        }
                    });
                };

                _createClass(ScrollSpy, null, [{
                    key: "VERSION",
                    get: function get() {
                        return VERSION$8;
                    }
                }, {
                    key: "Default",
                    get: function get() {
                        return Default$6;
                    }
                }]);

                return ScrollSpy;
            }();
        /**
         * ------------------------------------------------------------------------
         * Data Api implementation
         * ------------------------------------------------------------------------
         */


        $(window).on(Event$8.LOAD_DATA_API, function () {
            var scrollSpys = [].slice.call(document.querySelectorAll(Selector$8.DATA_SPY));
            var scrollSpysLength = scrollSpys.length;

            for (var i = scrollSpysLength; i--;) {
                var $spy = $(scrollSpys[i]);

                ScrollSpy._jQueryInterface.call($spy, $spy.data());
            }
        });
        /**
         * ------------------------------------------------------------------------
         * jQuery
         * ------------------------------------------------------------------------
         */

        $.fn[NAME$8] = ScrollSpy._jQueryInterface;
        $.fn[NAME$8].Constructor = ScrollSpy;

        $.fn[NAME$8].noConflict = function () {
            $.fn[NAME$8] = JQUERY_NO_CONFLICT$8;
            return ScrollSpy._jQueryInterface;
        };

        /**
         * ------------------------------------------------------------------------
         * Constants
         * ------------------------------------------------------------------------
         */

        var NAME$9 = 'tab';
        var VERSION$9 = '4.4.1';
        var DATA_KEY$9 = 'bs.tab';
        var EVENT_KEY$9 = "." + DATA_KEY$9;
        var DATA_API_KEY$7 = '.data-api';
        var JQUERY_NO_CONFLICT$9 = $.fn[NAME$9];
        var Event$9 = {
            HIDE: "hide" + EVENT_KEY$9,
            HIDDEN: "hidden" + EVENT_KEY$9,
            SHOW: "show" + EVENT_KEY$9,
            SHOWN: "shown" + EVENT_KEY$9,
            CLICK_DATA_API: "click" + EVENT_KEY$9 + DATA_API_KEY$7
        };
        var ClassName$9 = {
            DROPDOWN_MENU: 'nm_dropdown-menu',
            ACTIVE: 'active',
            DISABLED: 'disabled',
            FADE: 'fade',
            SHOW: 'show'
        };
        var Selector$9 = {
            DROPDOWN: '.nm_dropdown',
            NAV_LIST_GROUP: '.nav, .list-group',
            ACTIVE: '.active',
            ACTIVE_UL: '> li > .active',
            DATA_TOGGLE: '[data-toggle="tab"], [data-toggle="pill"], [data-toggle="list"]',
            DROPDOWN_TOGGLE: '.nm_dropdown-toggle',
            DROPDOWN_ACTIVE_CHILD: '> .nm_dropdown-menu .active'
        };
        /**
         * ------------------------------------------------------------------------
         * Class Definition
         * ------------------------------------------------------------------------
         */

        var Tab =
            /*#__PURE__*/
            function () {
                function Tab(element) {
                    this._element = element;
                } // Getters


                var _proto = Tab.prototype;

                // Public
                _proto.show = function show() {
                    var _this = this;

                    if (this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE && $(this._element).hasClass(ClassName$9.ACTIVE) || $(this._element).hasClass(ClassName$9.DISABLED)) {
                        return;
                    }

                    var target;
                    var previous;
                    var listElement = $(this._element).closest(Selector$9.NAV_LIST_GROUP)[0];
                    var selector = Util.getSelectorFromElement(this._element);

                    if (listElement) {
                        var itemSelector = listElement.nodeName === 'UL' || listElement.nodeName === 'OL' ? Selector$9.ACTIVE_UL : Selector$9.ACTIVE;
                        previous = $.makeArray($(listElement).find(itemSelector));
                        previous = previous[previous.length - 1];
                    }

                    var hideEvent = $.Event(Event$9.HIDE, {
                        relatedTarget: this._element
                    });
                    var showEvent = $.Event(Event$9.SHOW, {
                        relatedTarget: previous
                    });

                    if (previous) {
                        $(previous).trigger(hideEvent);
                    }

                    $(this._element).trigger(showEvent);

                    if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented()) {
                        return;
                    }

                    if (selector) {
                        target = document.querySelector(selector);
                    }

                    this._activate(this._element, listElement);

                    var complete = function complete() {
                        var hiddenEvent = $.Event(Event$9.HIDDEN, {
                            relatedTarget: _this._element
                        });
                        var shownEvent = $.Event(Event$9.SHOWN, {
                            relatedTarget: previous
                        });
                        $(previous).trigger(hiddenEvent);
                        $(_this._element).trigger(shownEvent);
                    };

                    if (target) {
                        this._activate(target, target.parentNode, complete);
                    } else {
                        complete();
                    }
                };

                _proto.dispose = function dispose() {
                    $.removeData(this._element, DATA_KEY$9);
                    this._element = null;
                } // Private
                ;

                _proto._activate = function _activate(element, container, callback) {
                    var _this2 = this;

                    var activeElements = container && (container.nodeName === 'UL' || container.nodeName === 'OL') ? $(container).find(Selector$9.ACTIVE_UL) : $(container).children(Selector$9.ACTIVE);
                    var active = activeElements[0];
                    var isTransitioning = callback && active && $(active).hasClass(ClassName$9.FADE);

                    var complete = function complete() {
                        return _this2._transitionComplete(element, active, callback);
                    };

                    if (active && isTransitioning) {
                        var transitionDuration = Util.getTransitionDurationFromElement(active);
                        $(active).removeClass(ClassName$9.SHOW).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
                    } else {
                        complete();
                    }
                };

                _proto._transitionComplete = function _transitionComplete(element, active, callback) {
                    if (active) {
                        $(active).removeClass(ClassName$9.ACTIVE);
                        var dropdownChild = $(active.parentNode).find(Selector$9.DROPDOWN_ACTIVE_CHILD)[0];

                        if (dropdownChild) {
                            $(dropdownChild).removeClass(ClassName$9.ACTIVE);
                        }

                        if (active.getAttribute('role') === 'tab') {
                            active.setAttribute('aria-selected', false);
                        }
                    }

                    $(element).addClass(ClassName$9.ACTIVE);

                    if (element.getAttribute('role') === 'tab') {
                        element.setAttribute('aria-selected', true);
                    }

                    Util.reflow(element);

                    if (element.classList.contains(ClassName$9.FADE)) {
                        element.classList.add(ClassName$9.SHOW);
                    }

                    if (element.parentNode && $(element.parentNode).hasClass(ClassName$9.DROPDOWN_MENU)) {
                        var dropdownElement = $(element).closest(Selector$9.DROPDOWN)[0];

                        if (dropdownElement) {
                            var dropdownToggleList = [].slice.call(dropdownElement.querySelectorAll(Selector$9.DROPDOWN_TOGGLE));
                            $(dropdownToggleList).addClass(ClassName$9.ACTIVE);
                        }

                        element.setAttribute('aria-expanded', true);
                    }

                    if (callback) {
                        callback();
                    }
                } // Static
                ;

                Tab._jQueryInterface = function _jQueryInterface(config) {
                    return this.each(function () {
                        var $this = $(this);
                        var data = $this.data(DATA_KEY$9);

                        if (!data) {
                            data = new Tab(this);
                            $this.data(DATA_KEY$9, data);
                        }

                        if (typeof config === 'string') {
                            if (typeof data[config] === 'undefined') {
                                throw new TypeError("No method named \"" + config + "\"");
                            }

                            data[config]();
                        }
                    });
                };

                _createClass(Tab, null, [{
                    key: "VERSION",
                    get: function get() {
                        return VERSION$9;
                    }
                }]);

                return Tab;
            }();
        /**
         * ------------------------------------------------------------------------
         * Data Api implementation
         * ------------------------------------------------------------------------
         */


        $(document).on(Event$9.CLICK_DATA_API, Selector$9.DATA_TOGGLE, function (event) {
            event.preventDefault();

            Tab._jQueryInterface.call($(this), 'show');
        });
        /**
         * ------------------------------------------------------------------------
         * jQuery
         * ------------------------------------------------------------------------
         */

        $.fn[NAME$9] = Tab._jQueryInterface;
        $.fn[NAME$9].Constructor = Tab;

        $.fn[NAME$9].noConflict = function () {
            $.fn[NAME$9] = JQUERY_NO_CONFLICT$9;
            return Tab._jQueryInterface;
        };

        /**
         * ------------------------------------------------------------------------
         * Constants
         * ------------------------------------------------------------------------
         */

        var NAME$a = 'toast';
        var VERSION$a = '4.4.1';
        var DATA_KEY$a = 'bs.toast';
        var EVENT_KEY$a = "." + DATA_KEY$a;
        var JQUERY_NO_CONFLICT$a = $.fn[NAME$a];
        var Event$a = {
            CLICK_DISMISS: "click.dismiss" + EVENT_KEY$a,
            HIDE: "hide" + EVENT_KEY$a,
            HIDDEN: "hidden" + EVENT_KEY$a,
            SHOW: "show" + EVENT_KEY$a,
            SHOWN: "shown" + EVENT_KEY$a
        };
        var ClassName$a = {
            FADE: 'fade',
            HIDE: 'hide',
            SHOW: 'show',
            SHOWING: 'showing'
        };
        var DefaultType$7 = {
            animation: 'boolean',
            autohide: 'boolean',
            delay: 'number'
        };
        var Default$7 = {
            animation: true,
            autohide: true,
            delay: 500
        };
        var Selector$a = {
            DATA_DISMISS: '[data-dismiss="toast"]'
        };
        /**
         * ------------------------------------------------------------------------
         * Class Definition
         * ------------------------------------------------------------------------
         */

        var Toast =
            /*#__PURE__*/
            function () {
                function Toast(element, config) {
                    this._element = element;
                    this._config = this._getConfig(config);
                    this._timeout = null;

                    this._setListeners();
                } // Getters


                var _proto = Toast.prototype;

                // Public
                _proto.show = function show() {
                    var _this = this;

                    var showEvent = $.Event(Event$a.SHOW);
                    $(this._element).trigger(showEvent);

                    if (showEvent.isDefaultPrevented()) {
                        return;
                    }

                    if (this._config.animation) {
                        this._element.classList.add(ClassName$a.FADE);
                    }

                    var complete = function complete() {
                        _this._element.classList.remove(ClassName$a.SHOWING);

                        _this._element.classList.add(ClassName$a.SHOW);

                        $(_this._element).trigger(Event$a.SHOWN);

                        if (_this._config.autohide) {
                            _this._timeout = setTimeout(function () {
                                _this.hide();
                            }, _this._config.delay);
                        }
                    };

                    this._element.classList.remove(ClassName$a.HIDE);

                    Util.reflow(this._element);

                    this._element.classList.add(ClassName$a.SHOWING);

                    if (this._config.animation) {
                        var transitionDuration = Util.getTransitionDurationFromElement(this._element);
                        $(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
                    } else {
                        complete();
                    }
                };

                _proto.hide = function hide() {
                    if (!this._element.classList.contains(ClassName$a.SHOW)) {
                        return;
                    }

                    var hideEvent = $.Event(Event$a.HIDE);
                    $(this._element).trigger(hideEvent);

                    if (hideEvent.isDefaultPrevented()) {
                        return;
                    }

                    this._close();
                };

                _proto.dispose = function dispose() {
                    clearTimeout(this._timeout);
                    this._timeout = null;

                    if (this._element.classList.contains(ClassName$a.SHOW)) {
                        this._element.classList.remove(ClassName$a.SHOW);
                    }

                    $(this._element).off(Event$a.CLICK_DISMISS);
                    $.removeData(this._element, DATA_KEY$a);
                    this._element = null;
                    this._config = null;
                } // Private
                ;

                _proto._getConfig = function _getConfig(config) {
                    config = _objectSpread2({}, Default$7, {}, $(this._element).data(), {}, typeof config === 'object' && config ? config : {});
                    Util.typeCheckConfig(NAME$a, config, this.constructor.DefaultType);
                    return config;
                };

                _proto._setListeners = function _setListeners() {
                    var _this2 = this;

                    $(this._element).on(Event$a.CLICK_DISMISS, Selector$a.DATA_DISMISS, function () {
                        return _this2.hide();
                    });
                };

                _proto._close = function _close() {
                    var _this3 = this;

                    var complete = function complete() {
                        _this3._element.classList.add(ClassName$a.HIDE);

                        $(_this3._element).trigger(Event$a.HIDDEN);
                    };

                    this._element.classList.remove(ClassName$a.SHOW);

                    if (this._config.animation) {
                        var transitionDuration = Util.getTransitionDurationFromElement(this._element);
                        $(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
                    } else {
                        complete();
                    }
                } // Static
                ;

                Toast._jQueryInterface = function _jQueryInterface(config) {
                    return this.each(function () {
                        var $element = $(this);
                        var data = $element.data(DATA_KEY$a);

                        var _config = typeof config === 'object' && config;

                        if (!data) {
                            data = new Toast(this, _config);
                            $element.data(DATA_KEY$a, data);
                        }

                        if (typeof config === 'string') {
                            if (typeof data[config] === 'undefined') {
                                throw new TypeError("No method named \"" + config + "\"");
                            }

                            data[config](this);
                        }
                    });
                };

                _createClass(Toast, null, [{
                    key: "VERSION",
                    get: function get() {
                        return VERSION$a;
                    }
                }, {
                    key: "DefaultType",
                    get: function get() {
                        return DefaultType$7;
                    }
                }, {
                    key: "Default",
                    get: function get() {
                        return Default$7;
                    }
                }]);

                return Toast;
            }();
        /**
         * ------------------------------------------------------------------------
         * jQuery
         * ------------------------------------------------------------------------
         */


        $.fn[NAME$a] = Toast._jQueryInterface;
        $.fn[NAME$a].Constructor = Toast;

        $.fn[NAME$a].noConflict = function () {
            $.fn[NAME$a] = JQUERY_NO_CONFLICT$a;
            return Toast._jQueryInterface;
        };

        exports.Alert = Alert;
        exports.Button = Button;
        exports.Carousel = Carousel;
        exports.Collapse = Collapse;
        exports.Dropdown = Dropdown;
        exports.Modal = Modal;
        exports.Popover = Popover;
        exports.Scrollspy = ScrollSpy;
        exports.Tab = Tab;
        exports.Toast = Toast;
        exports.Tooltip = Tooltip;
        exports.Util = Util;

        Object.defineProperty(exports, '__esModule', { value: true });

    })));
    //# sourceMappingURL=bootstrap.js.map

</script>