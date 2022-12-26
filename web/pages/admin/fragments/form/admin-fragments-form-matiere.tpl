%4$s
<div class="card card-primary card-outline">
	<form action="#" method="post" id="post-edit">
    	<div class="card-header"><strong>%1$s</strong></div>
        <div class="card-body">
        	<div class="row">
          		<div class="col-md">
            		<div class="form-floating mb-3">
              			<input id="labelMatiere" type="text" class="form-control form-control-sm" placeholder="Libellé de la Matière" value="%5$s" name="labelMatiere">
              			<label for="labelMatiere">Libellé</label>
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
        