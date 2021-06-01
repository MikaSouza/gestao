<div class="row">
									<div class="col-lg-4">
										<div class="card client-card">
											<div class="card-body text-center">
												<h5 class=" client-name"><?= $vROBJETOHOME['CLINOMEFANTASIA']; ?></h5> <br />
												<span class="text-muted mr-3"><i class="fas fa-crosshairs mr-2 text-info"></i><?= $vRENDERECOHOME['CIDDESCRICAO']; ?> <?= $vRENDERECOHOME['ESTSIGLA']; ?></span><br />
												<span class="text-muted"><i class="	fas fa-phone mr-2 text-info"></i>
													<?php if (isset($vRCONTATOHOME['CONFONE'])) echo $vRCONTATOHOME['CONFONE'];
													else echo '';
													?>
													<?php if (isset($vRCONTATOHOME['CONCELULAR'])) echo $vRCONTATOHOME['CONCELULAR'];
													else echo '';
													?>
												</span>
											</div>
										</div>
									</div>
									<?php $contatos_cliente = listContatosHome($_GET['id']);
									foreach ($contatos_cliente['dados'] as $contatos_cliente) : ?>
										<div class="col-lg-4">
											<div class="card client-card">
												<div class="card-body text-center">
													<h5 class=" client-name">
														<?= $contatos_cliente['CONNOME']; ?><br /><?= $contatos_cliente['CONCARGO']; ?>
													</h5>
													<span class="text-muted mr-3"><i class="dripicons-mail mr-2 text-info"></i><?= $contatos_cliente['CONEMAIL']; ?></span><br>
													<!--<span class="text-muted"><i class="	fas fa-phone mr-2 text-info"></i><? //= $contatos_cliente['CONCELULAR'];
																																?></span>-->
													<span class="text-muted"><i class="	fas fa-phone mr-2 text-info"></i><?= $contatos_cliente['CONFONE']; ?></span>
													&nbsp; &nbsp;<span class="text-muted mr-3"><i class="far fa-address-book mr-2 text-info"></i>Cargo: &nbsp;<?= $vRCONTATOHOME['CONCARGO']; ?></span><br>

												</div>
											</div>
										</div></br>
									<?php endforeach ?>

								</div>