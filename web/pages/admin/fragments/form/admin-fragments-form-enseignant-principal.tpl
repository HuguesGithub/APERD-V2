%4$s
<div class="card card-primary card-outline">
	<form action="#" method="post" id="post-edit">
    	<div class="card-header"><strong>%1$s</strong></div>
        <div class="card-body">
        
            <div class="row">
              <div class="col-md">
                <div class="form-floating mb-3">
                	<select class="form-select" id="enseignantId" name="enseignantId" aria-label="Liste des enseignants" required>%5$s</select>
               		<label for="enseignantId">Enseignant</label>
                </div>
              </div>
              <div class="col-md">
                <div class="form-floating mb-3">
                	<select class="form-select" id="divisionId" name="divisionId" aria-label="Liste des divisions" required>%6$s</select>
               		<label for="divisionId">Division</label>
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
