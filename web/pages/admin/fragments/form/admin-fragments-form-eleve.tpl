%4$s
<div class="card card-primary card-outline">
	<form action="#" method="post" id="post-edit">
    	<div class="card-header"><strong>%1$s</strong></div>
        <div class="card-body">
        
            <div class="row">
              <div class="col-md">
                <div class="form-floating mb-3">
                  <input id="nomEleve" type="text" class="form-control form-control-sm required" placeholder="Nom Élève" value="%5$s" name="nomEleve" required>
                  <label for="nomEleve">Nom Élève</label>
                </div>
              </div>
              <div class="col-md">
                <div class="form-floating mb-3">
                  <input id="prenomEleve" type="text" class="form-control form-control-sm" placeholder="Prénom Élève" value="%6$s" name="prenomEleve">
                  <label for="prenomEleve">Prénom Élève</label>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md">
                <div class="form-floating mb-3">
                	<select class="form-select" id="divisionId" name="divisionId" aria-label="Liste des divisions" required>%7$s</select>
               		<label for="divisionId">Division</label>
                </div>
              </div>
				<div class="col-md">
					<div class="form-control pt-0 pb-0 mb-3" style="height: inherit;">
						<div style="padding-top: 16px;padding-bottom: 8px;" class="input-group input-group-lg">
                  			<label for="delegue" class="label-checkbox">Délégué ?</label>
                  			<div style="border: 0 none; margin-left: 50px;">
                    			<input id="delegue" type="checkbox" class="form-check-input" value="1" name="delegue" style="position: initial;"%8$s>
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
