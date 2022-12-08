<div class="card card-primary card-outline">
	<form action="#" method="post" id="post-edit">
    	<div class="card-header"><strong>%1$s</strong></div>
        <div class="card-body">
            <div class="row">
              <div class="col-md">
                <div class="form-floating mb-3">
                  <input id="nomAdulte" type="text" class="form-control form-control-sm required" placeholder="Nom Parent" value="%4$s" name="nomAdulte" required>
                  <label for="nomAdulte">Nom Parent</label>
                </div>
              </div>
              <div class="col-md">
                <div class="form-floating mb-3">
                  <input id="prenomAdulte" type="text" class="form-control form-control-sm" placeholder="Prénom Parent" value="%5$s" name="prenomAdulte">
                  <label for="prenomAdulte">Prénom Parent</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md">
                <div class="form-floating mb-3">
                  <input id="mailAdulte" type="text" class="form-control required" placeholder="Mail Parent" value="%6$s" name="mailAdulte" required>
                  <label for="mailAdulte">Mail Parent</label>
                </div>
              </div>
				<div class="col-md">
					<div class="form-control pt-0 pb-0 mb-3" style="height: inherit;">
						<div style="padding-top: 16px;padding-bottom: 8px;" class="input-group input-group-lg">
                  			<label for="adherent" class="label-checkbox">Adhérent ?</label>
                  			<div style="border: 0 none; margin-left: 50px;">
                    			<input id="adherent" type="checkbox" class="form-check-input" value="1" name="adherent" style="position: initial;"%7$s>
                  			</div>
                		</div>
                	</div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="btn-group btn-group-toggle">
	            <input type="submit" name="postAction" value="%1$s" class="btn btn-primary btn-sm"/>
                <input type="hidden" name="id" value="%2$s"/>
                <a href="%3$s" class="btn btn-default btn-secondary btn-sm">Annuler</a>
            </div>
        </div>
    </form>
</div>
        