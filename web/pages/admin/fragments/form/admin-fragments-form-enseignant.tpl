%4$s
<div class="card card-primary card-outline">
	<form action="#" method="post" id="post-edit">
    	<div class="card-header"><strong>%1$s</strong></div>
        <div class="card-body">
        
            <div class="row">
              <div class="col-md">
                <div class="form-floating mb-3">
                  <input id="nomEnseignant" type="text" class="form-control form-control-sm required" placeholder="Nom Enseignant" value="%6$s" name="nomEnseignant" required>
                  <label for="nomEnseignant">Nom Enseignant</label>
                </div>
              </div>
              <div class="col-md">
                <div class="form-floating mb-3">
                  <input id="prenomEnseignant" type="text" class="form-control form-control-sm" placeholder="Prénom Enseignant" value="%7$s" name="prenomEnseignant">
                  <label for="prenomEnseignant">Prénom Enseignant</label>
                </div>
              </div>
            </div>
            
            <div class="row">
	       		<div class="col-md">
            		<div class="form-floating mb-3">
              			<input id="genre" type="text" class="form-control form-control-sm" placeholder="Genre" value="%4$s" name="genre">
              			<label for="genre">Genre</label>
            		</div>
          		</div>
	       		<div class="col-md">
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
