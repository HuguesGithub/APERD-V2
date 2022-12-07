	<div class="card card-primary card-outline">
		<div class="card-header"><h3 class="card-title">%1$s</h3></div>
		<div class="card-body p-0">
			%2$s

			<div class="table-responsive %3$s-list">
				<table class="table table-hover table-striped table-sm" aria-describedby="%4$s">
					<thead>%5$s</thead>
					<tbody>%6$s</tbody>
				</table>
			</div>
		</div>

		<div class="card-footer p-0">
			%2$s
			<div class="mailbox-controls">
				<button type="button" class="btn btn-default btn-sm" title="Rafraîchir la liste"><a href="/admin?onglet=library&amp;subOnglet=index&amp;curPage=1" class="text-white"><i class="fa-solid fa-arrows-rotate"></i></a></button>&nbsp;
				<button type="button" class="btn btn-default btn-sm ajaxAction" title="Exporter la liste" data-trigger="click" data-ajax="csvExport" data-natureid=""><i class="fa-solid fa-download"></i></button>
				<div class="float-right">
					<button type="button" class="btn btn-default btn-sm disabled text-white"><i class="fa-solid fa-caret-left"></i></button>&nbsp;1 - 10 sur 790&nbsp;
					<button type="button" class="btn btn-default btn-sm "><a href="/admin?onglet=library&amp;subOnglet=index&amp;curPage=2" class="text-white"><i class="fa-solid fa-caret-right"></i></a></button>
				</div>
			</div>
		</div>
	</div>
