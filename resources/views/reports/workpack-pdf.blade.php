@php use Illuminate\Support\Collection; @endphp
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{base_path('resources/views/reports/css/bootstrap.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="{{base_path('resources/views/reports/css/pdf.css') }}">
</head>
<body>
@php
    $panelCount = $panels->count();
    $workpackPanelRowsPerPage = config('jetworks145.pdf.per_page_panels');
    $pageCount = ceil( $panelCount / $workpackPanelRowsPerPage);
    $adjustedPageCount = $pageCount + 1;
@endphp
<div class="container2 page-break">
    @include('reports.header', [ 'workpack' => $workpack, 'aeroplane' => $aeroplane, 'airframe' => $airframe, 'pageCount' => $adjustedPageCount ]  )
    <div class="cover-page text-center">
        <h1>{{ $workpack->name }}</h1>
        <hr>
        <div class="aeroplane-details">
            <span class="aeroplane-detail">
                <img src="{{base_path('resources/views/reports/img/icon-tail@2x.png') }}" alt="" height="20px"
                     width="20px">
                <span class="aeroplane-tailnumber">{{ $aeroplane->name }}</span>
            </span>
            <span class="aeroplane-detail">
                 <img src="{{base_path('resources/views/reports/img/icon-aeroplane@2x.png') }}" alt="" height="20px"
                      width="20px">
                <span class="airframe-name">{{ $airframe->name }}</span>
            </span>
            @if( $airframe->revision)
                <span class="aeroplane-detail">
                    <span class="airframe-revision"><strong>Revision Number:</strong>{{ $airframe->revision }}</span>
                </span>
            @endif
        </div>
    </div>
    @include('reports.footer')
</div>
    @foreach ($schematics as $key => $schematic)
        <div class="container2">
        @foreach ($schematic->panels as $index => $panel)
            @php
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
                <div class="schematic-header pb-1 clearfix">
                    <div class="schematic-name schematic-left">
                        <strong>{{ $schematic->schematic->name }}</strong>
                    </div>
                    <div class="schematic-pagenum schematic-right">
                        <span class="pagenum">Sheet&nbsp;</span>
                    </div>
                </div>
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
                <div class="schematic-header pb-1 clearfix">
                    <div class="schematic-name schematic-left">
                        <strong>{{ $schematic->schematic->name }}</strong>
                    </div>
                    <div class="schematic-pagenum schematic-right">
                        <span class="pagenum">Sheet&nbsp;</span>
                    </div>
                </div>
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
    @endforeach
</body>
</html>
