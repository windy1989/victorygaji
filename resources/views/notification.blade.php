<!--**********************************
    Content body start
***********************************-->
<style>
    @media (min-width: 500px){
        #notification-letter-datatable td:nth-of-type(1), #notification-letter-datatable td:nth-of-type(2), #notification-letter-datatable td:nth-last-of-type(1) {
            background-color:rgb(255, 233, 173) !important;
        }
    }
</style>
<div class="content-body">
    <!-- container starts -->
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ env('APP_NAME') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ Str::title(str_replace('_',' ',Request::segment(1))) }}</a></li>
            </ol>
        </div>
        <!-- row -->
        <!-- Row starts -->
        <div class="row">
            <!-- Column starts -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-block">
                        <h4 class="card-title">Daftar {{ $title }}</h4>
                        <div>
                            <button type="button" class="btn btn-primary mb-2" onclick="loadDataTableNotification();">Refresh</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="notification-datatable" class="display nowrap" style="min-width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Pengguna</th>
                                        <th>Judul</th>
                                        <th>Keterangan</th>
                                        <th>Tgl.Kejadian</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column ends -->
        </div>
        <!-- Row ends -->
    </div>
    <!-- container ends -->
</div>
<!--**********************************
        Content body end
    ***********************************-->