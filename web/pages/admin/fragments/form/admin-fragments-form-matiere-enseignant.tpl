%4$s
<div class="card card-primary card-outline">
	<form action="#" method="post" id="post-edit">
    	<div class="card-header"><strong>%1$s</strong></div>
        <div class="card-body">
        
            <div class="row">
              <div class="col-md">
                <div class="form-floating mb-3">
                	<select class="form-select" id="matiereId" name="matiereId" aria-label="Liste des matières" required>%5$s</select>
               		<label for="matiereId">Matière</label>
                </div>
              </div>
              <div class="col-md">
                <div class="form-floating mb-3">
                	<select class="form-select" id="enseignantId" name="enseignantId" aria-label="Liste des enseignants" required>%6$s</select>
               		<label for="enseignantId">Enseignant</label>
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
