        <!--**********************************
            Content body start
        ***********************************-->
        
		<style>
			.modal-body{
				height: 75vh;
				overflow-y: auto;
			}
            #invoice-datatable td:nth-of-type(1), #invoice-datatable td:nth-of-type(2), #invoice-datatable td:nth-last-of-type(1) {
                background-color:rgb(255, 233, 173) !important;
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
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">Daftar {{ $title }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-xl-4">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="profile-statistics">
                                                        <div class="text-center">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <h3 class="m-b-0">150</h3><span>Follower</span>
                                                                </div>
                                                                <div class="col">
                                                                    <h3 class="m-b-0">140</h3><span>Place Stay</span>
                                                                </div>
                                                                <div class="col">
                                                                    <h3 class="m-b-0">45</h3><span>Reviews</span>
                                                                </div>
                                                            </div>
                                                            <div class="mt-4">
                                                                <a href="javascript:void(0);" class="btn btn-primary mb-1 me-1">Follow</a> 
                                                                <a href="javascript:void(0);" class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#sendMessageModal">Send Message</a>
                                                            </div>
                                                        </div>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="sendMessageModal">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Send Message</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form class="comment-form">
                                                                            <div class="row"> 
                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-3">
                                                                                        <label class="text-black font-w600 form-label">Name <span class="required">*</span></label>
                                                                                        <input type="text" class="form-control" value="Author" name="Author" placeholder="Author">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-3">
                                                                                        <label class="text-black font-w600 form-label">Email <span class="required">*</span></label>
                                                                                        <input type="text" class="form-control" value="Email" placeholder="Email" name="Email">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-12">
                                                                                    <div class="mb-3">
                                                                                        <label class="text-black font-w600 form-label">Comment</label>
                                                                                        <textarea rows="8" class="form-control" name="comment" placeholder="Comment"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-12">
                                                                                    <div class="mb-3 mb-0">
                                                                                        <input type="submit" value="Post Comment" class="submit btn btn-primary" name="submit">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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