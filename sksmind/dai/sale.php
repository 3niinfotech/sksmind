<!DOCTYPE html>
<?php 

include("../database.php");
include("../variable.php");
include("head.php");
?>	
<html lang="en">
	

	<body class="no-skin">
		<?php include("header.php");?>
		<div class="main-container ace-save-state" id="main-container">
			
			<?php include("left.php");?>
			
			<div class="main-content">
				<div class="main-content-inner">					
					<div class="page-content">
						<div class="page-header">
							<h1>
								Account Ledger								
							</h1>
						</div><!-- /.page-header -->
						<div class="row">
					<div class="col-xs-6">
					
						<!-- div.table-responsive -->

						<!-- div.dataTables_borderWrap -->
						<div>
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</th>
										<th>Ledger</th>
										<th>Under Group</th>
										<th>Edit / Delete</th>
									</tr>
								</thead>

								<tbody>
									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">app.com</a>
										</td>
										<td>$45</td>
										

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">base.com</a>
										</td>
										<td>$35</td>
										
										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										
										<td>
											<a href="#">base.com</a>
										</td>
										<td>$35</td>

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">best.com</a>
										</td>
										<td>$75</td>
										

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">pro.com</a>
										</td>
										<td>$55</td>
										

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">team.com</a>
										</td>
										<td>$40</td>
										

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">up.com</a>
										</td>
										<td>$95</td>
									

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">view.com</a>
										</td>
										<td>$45</td>
									
										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">nice.com</a>
										</td>
										<td>$38</td>
									
										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">fine.com</a>
										</td>
										<td>$25</td>
									

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">good.com</a>
										</td>
										<td>$50</td>
									
										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">great.com</a>
										</td>
										<td>$55</td>
								
										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">shine.com</a>
										</td>
										<td>$25</td>
									

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">rise.com</a>
										</td>
										<td>$42</td>
									

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">above.com</a>
										</td>
										<td>$35</td>
									

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">share.com</a>
										</td>
										<td>$30</td>
									

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">fair.com</a>
										</td>
										<td>$35</td>
										

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">year.com</a>
										</td>
										<td>$48</td>
									
										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">day.com</a>
										</td>
										<td>$55</td>
									

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">light.com</a>
										</td>
										<td>$40</td>
									
										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">sight.com</a>
										</td>
										<td>$58</td>
									

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">right.com</a>
										</td>
										<td>$50</td>
									
										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td class="center">
											<label class="pos-rel">
												<input type="checkbox" class="ace" />
												<span class="lbl"></span>
											</label>
										</td>

										<td>
											<a href="#">once.com</a>
										</td>
										<td>$20</td>
										

										<td>
											<div class="hidden-sm hidden-xs action-buttons">
												
												<a class="green" href="#">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>

												<a class="red" href="#">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</div>

											<div class="hidden-md hidden-lg">
												<div class="inline pos-rel">
													<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
														<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
													</button>

													<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
														

														<li>
															<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																<span class="green">
																	<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
																</span>
															</a>
														</li>

														<li>
															<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																<span class="red">
																	<i class="ace-icon fa fa-trash-o bigger-120"></i>
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				

								
						<div class="col-xs-12 col-sm-5 right">
							<div class="widget-box">
								<div class="widget-header">
									<h4 class="widget-title">Create New Account Ledger</h4>

									<span class="widget-toolbar">
										<a href="#" data-action="reload">
											<i class="ace-icon fa fa-refresh"></i>
										</a>

										<a href="#" data-action="collapse">
											<i class="ace-icon fa fa-chevron-up"></i>
										</a>

										<a href="#" data-action="close">
											<i class="ace-icon fa fa-times"></i>
										</a>
									</span>
								</div>
								<form class="form-horizontal" role="form">
								<div class="widget-body">
									<div class="widget-main">
										<br>
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Ledger Name </label>

											<div class="col-sm-9">
												<input id="form-field-1" placeholder="Ledger Name" class="col-xs-10" type="text">
											</div>
										</div>
									<br>
										<div class="form-group">
											<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Under Group </label>
											<div class="col-sm-9">
											<select class="col-xs-10" id="form-field-select-1">
												<option value=""></option>
												<option value="AL">Alabama</option>
												<option value="AK">Alaska</option>
												<option value="AZ">Arizona</option>
												<option value="AR">Arkansas</option>
												<option value="CA">California</option>
												<option value="CO">Colorado</option>
												<option value="CT">Connecticut</option>
												<option value="DE">Delaware</option>
												<option value="FL">Florida</option>
												<option value="GA">Georgia</option>
											
											</select>
											</div>
										</div>
											<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
									</div>
									</div>
								</div>
								</form>
							</div>
										</div><!-- /.span -->
															
					</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<?php
			include("footer.php");
		?>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="assets/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		
		<script src="assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="assets/js/excanvas.min.js"></script>
		<![endif]-->
	
		<!-- page specific plugin scripts -->
		<script src="assets/js/jquery.dataTables.min.js"></script>
		<script src="assets/js/jquery.dataTables.bootstrap.min.js"></script>
		<script src="assets/js/dataTables.buttons.min.js"></script>
		<script src="assets/js/buttons.flash.min.js"></script>
		<script src="assets/js/buttons.html5.min.js"></script>
		<script src="assets/js/buttons.print.min.js"></script>
		<script src="assets/js/buttons.colVis.min.js"></script>
		<script src="assets/js/dataTables.select.min.js"></script>

		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>
		<script type="text/javascript">
			jQuery(function($) {
				//initiate dataTables plugin
				var myTable = 
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					bAutoWidth: false,
					"aoColumns": [
					  { "bSortable": false },
					  null, null,
					  { "bSortable": false }
					],
					"aaSorting": [],
					
					
					//"bProcessing": true,
			        //"bServerSide": true,
			        //"sAjaxSource": "http://127.0.0.1/table.php"	,
			
					//,
					//"sScrollY": "200px",
					//"bPaginate": false,
			
					//"sScrollX": "100%",
					//"sScrollXInner": "120%",
					//"bScrollCollapse": true,
					//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
					//you may want to wrap the table inside a "div.dataTables_borderWrap" element
			
					//"iDisplayLength": 50
			
			
					select: {
						style: 'multi'
					}
			    } );
			
				
				
				$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
				
				new $.fn.dataTable.Buttons( myTable, {
					buttons: [
					  {
						"extend": "colvis",
						"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
						"className": "btn btn-white btn-primary btn-bold",
						columns: ':not(:first):not(:last)'
					  },
					  {
						"extend": "copy",
						"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "csv",
						"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "excel",
						"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "pdf",
						"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "print",
						"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
						"className": "btn btn-white btn-primary btn-bold",
						autoPrint: false,
						message: 'This print was produced using the Print button for DataTables'
					  }		  
					]
				} );
				myTable.buttons().container().appendTo( $('.tableTools-container') );
				
				//style the message box
				var defaultCopyAction = myTable.button(1).action();
				myTable.button(1).action(function (e, dt, button, config) {
					defaultCopyAction(e, dt, button, config);
					$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
				});
				
				
				var defaultColvisAction = myTable.button(0).action();
				myTable.button(0).action(function (e, dt, button, config) {
					
					defaultColvisAction(e, dt, button, config);
					
					
					if($('.dt-button-collection > .dropdown-menu').length == 0) {
						$('.dt-button-collection')
						.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
						.find('a').attr('href', '#').wrap("<li />")
					}
					$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
				});
			
				////
			
				setTimeout(function() {
					$($('.tableTools-container')).find('a.dt-button').each(function() {
						var div = $(this).find(' > div').first();
						if(div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
						else $(this).tooltip({container: 'body', title: $(this).text()});
					});
				}, 500);
				
				
				
				
				
				myTable.on( 'select', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
					}
				} );
				myTable.on( 'deselect', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
					}
				} );
			
			
			
			
				/////////////////////////////////
				//table checkboxes
				$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
				
				//select/deselect all rows according to table header checkbox
				$('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$('#dynamic-table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) myTable.row(row).select();
						else  myTable.row(row).deselect();
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
					var row = $(this).closest('tr').get(0);
					if(this.checked) myTable.row(row).deselect();
					else myTable.row(row).select();
				});
			
			
			
				$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
					e.stopImmediatePropagation();
					e.stopPropagation();
					e.preventDefault();
				});
				
				
				
				//And for the first simple table, which doesn't have TableTools or dataTables
				//select/deselect all rows according to table header checkbox
				var active_class = 'active';
				$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$(this).closest('table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
						else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
					var $row = $(this).closest('tr');
					if($row.is('.detail-row ')) return;
					if(this.checked) $row.addClass(active_class);
					else $row.removeClass(active_class);
				});
			
				
			
				/********************************/
				//add tooltip for small view action buttons in dropdown menu
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				
				//tooltip placement on right or left
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
				
				
				
				
				/***************/
				$('.show-details-btn').on('click', function(e) {
					e.preventDefault();
					$(this).closest('tr').next().toggleClass('open');
					$(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
				});
				/***************/
				
				
				
				
				
				/**
				//add horizontal scrollbars to a simple table
				$('#simple-table').css({'width':'2000px', 'max-width': 'none'}).wrap('<div style="width: 1000px;" />').parent().ace_scroll(
				  {
					horizontal: true,
					styleClass: 'scroll-top scroll-dark scroll-visible',//show the scrollbars on top(default is bottom)
					size: 2000,
					mouseWheelLock: true
				  }
				).css('padding-top', '12px');
				*/
			
			
			})
		</script>

		
	</body>
</html>







	