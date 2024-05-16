@php use Illuminate\Support\Collection; @endphp
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{base_path('resources/views/reports/bootstrap.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700&display=swap"
          rel="stylesheet">
    <style>
        /** Define the margins of your page **/
        @page {
            margin: 200px 10px 80px 10px;
        }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
        }

        .header {
            position: fixed;
            top: 10px;
            left: 0px;
            right: 0px;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        .cover-page{
            position: absolute;
            top: 300px;
            left: 20px;
            right: 20px;
        }

        .header-left,
        .header-right{
            float: left;
            display: block;
            width: 50%;
        }

        .header-right{
            text-align: right;
        }

        .logo {
            display: inline-block;
            margin-bottom: 10px
        }

        .header-h2 {
            font-size: 28px;
        }

        .header-h3 {
            font-size: 17px;
        }

        table {
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #BCC1C7;
        }

        th {
            padding: 8px;
            background-color: #D1D5DB;
            font-weight: normal;
            font-size: 14px;
        }

        td {
            width: 20%;
            vertical-align: center;
        }

        tr td {
            background-color: white;
        }

        tr:nth-of-type(2n+0) td {
            background-color: #ECF0F7;
        }

        thead th:first-of-type{
            border-top-left-radius: 5px;
        }
        thead th:last-of-type{
            border-top-right-radius: 5px;
        }

        .td-content{
            display: block;
            position: relative;
            padding: 5px 8px;
        }

        .name,
        .initials,
        .panel {
            display: inline-block;
            font-weight: bold;
        }

        .stamp {
            position: absolute;
            display: inline-block;
            top: 6px;
            right: 6px;
            border: 2px solid black;
            border-radius: 100%;
            width: 40px;
            height: 40px;
            transform: rotate(10deg);
        }

        .panel {
            font-size: 15px;
        }

        .name {
            margin-top: 4px;
            font-size: 13px;
        }

        .initials {
            display: inline-block;
            max-width: 30px;
            font-size: 11px;
            line-height: 11px;
            font-weight: bold;
            padding: 7px 3px;
            text-align: center;
        }

        .pagenum:after {
            content: counter(page);
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

<div class="container page-break">

    @include('reports.header', [ 'workpack' => $workpack, 'aeroplane' => $aeroplane, 'airframe' => $airframe ]  )

    <div class="cover-page  text-center">
        <h1>{{ $workpack->name }}</h1>
        <hr>
        <div class="aeroplane-details">
            <span class="aeroplane-tailnumber">{{ $aeroplane->name }}</span>
            <span class="airframe-name">{{ $airframe->name }}</span>
            <span class="airframe-revision"><strong>Revision Number:</strong>{{ $airframe->revision }}</span>
        </div>
    </div>

    @include('reports.footer')


</div>


<div class="container">

    @foreach ($panels as $index => $panel)
        @php
            $panelCount = $panels->count();
            $workpackPanelRowsPerPage = config('jetworks145.pdf.per_page_panels');
            $pageCount = ceil( $panelCount / $workpackPanelRowsPerPage);
            $startTable = false;
            $endTable = false;
            $currentPage = ceil( ($index + 1 ) / $workpackPanelRowsPerPage );
            $lastPage = false;
            if( $index === 0 ){
                $startTable = true;
            }
             $mod = $index % $workpackPanelRowsPerPage;
            if( $mod === ( $workpackPanelRowsPerPage - 1 ) ){
                if( $pageCount != $currentPage ){
                    $endTable = true;
                }else{
                    $lastPage = true;
                }
           }
        @endphp
        @if( $startTable )
            <table class="table table-bordered table-striped text-center align-middle page-break">
                <thead>
                <tr>
                    <th>Panel Number</th>
                    <th>Removed</th>
                    <th>Pre-Fit Inspection</th>
                    <th>Fitted</th>
                    <th>Cleared Inspection</th>
                    <th>Sealed</th>
                </tr>
                </thead>
                <tbody>
                @endif
                <tr>
                    <td class="align-middle fw-bold"><span class="panel">{{ $panel->panel->name }}</span></td>
                    @foreach ( $panel->getDataForReportRow()  as $index => $workpack_panel_task )
                        @php
                            $user = $users->get( $workpack_panel_task->user_id );
                            $signature = $user_signatures[ $user->id] ?? $user->getEmbeddedData('signature');
                            $user_signatures[ $user->id ] = $signature;
                        @endphp
                        @if( $workpack_panel_task->isStepCompleted() )
                            <td class="td-cell align-middle">
                                    <span class="td-content">
                                        <img class="signature" src="{{ $user->getEmbeddedData('signature') }}" alt=""/>
                                        <span class="name">{{ $user->fname }} {{ $user->lname }}</span>
                                        @if( $user->stamp )
                                            @if( in_array($workpack_panel_task->workpack_panel_step_id, [3,5,6] ) )
                                                <span class="stamp">
                                                <span class="initials">{{ $user->stamp }}</span>
                                            </span>
                                            @endif
                                        @endif
                                    </span>
                            </td>
                        @endif
                    @endforeach
                </tr>
                @if( $endTable )
                </tbody>
            </table>
            <table class="table table-bordered text-center align-middle {{ $lastPage ? '' : 'page-break'  }}">
                <thead>
                <tr>
                    <th>Panel Number</th>
                    <th>Removed</th>
                    <th>Pre-Fit Inspection</th>
                    <th>Fitted</th>
                    <th>Cleared Inspection</th>
                    <th>Sealed</th>
                </tr>
                @endif
                @if( $loop->last )
                </thead>
            </table>
        @endif
    @endforeach
</div>

</body>
</html>
