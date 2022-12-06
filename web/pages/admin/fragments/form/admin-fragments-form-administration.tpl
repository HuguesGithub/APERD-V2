<div class="card bg-light">
	<form action="#" method="post" id="post-edit">
    	<div class="card-header"><strong>%1$s</strong></div>
        <div class="card-body">
        	<div class="row">
          		<div class="col-md">
            		<div class="form-floating mb-3">
              			<input id="genre" type="text" class="form-control form-control-sm" placeholder="Genre" value="%4$s" name="genre">
              			<label for="genre">Genre</label>
            		</div>
          		</div>
          		<div class="col-md">
            		<div class="form-floating mb-3">
              			<input id="nomTitulaire" type="text" class="form-control form-control-sm" placeholder="Nom Titulaire" value="%5$s" name="nomTitulaire" required>
              			<label for="nomTitulaire">Nom Titulaire</label>
            		</div>
          		</div>
        	</div>
        	<div class="row">
          		<div class="col-md">
            		<div class="form-floating mb-3">
              			<input id="labelPoste" type="text" class="form-control form-control-sm" placeholder="Libellé Poste" value="%6$s" name="labelPoste" required>
              			<label for="labelPoste">Libellé Poste</label>
            		</div>
          		</div>
        	</div>
        </div>
        <div class="card-footer">
            <div class="btn-group btn-group-toggle">
	            <input type="submit" name="postAction" value="%1$s" class="btn btn-primary btn-lg"/>
                <input type="hidden" name="id" value="%2$s"/>
                <a href="%3$s" class="btn btn-outline-dark btn-lg">Annuler</a>
            </div>
        </div>
    </form>
</div>
        