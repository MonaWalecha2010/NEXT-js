<?php
?>
<div class="header container2">
    <div class="report-header clearfix">
        <div class="header-left">
            <span class="logo d-block">
                <img src="{{ asset('img/logo.png') }}" alt="">
            </span>
        </div>
        <div class="header-right">
            <h2 class="header-h2">Panel Control Sheet</h2>
        </div>
    </div>
    <div class="report-header pb-2 mb-2 clearfix border-bottom">
        <div class="header-left">
            <!--<span class="header-h3"><span class="pagenum">Sheet&nbsp;</span>&nbsp;of&nbsp;<span>{{ $pageCount }}</span></span>-->
        </div>
        <div class="header-right">
            <p class="header-h3">WPN: <strong>{{ $workpack->name }}</strong></p>
        </div>
    </div>
</div>
