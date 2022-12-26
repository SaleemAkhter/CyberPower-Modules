<li class="lu-list__item" v-for="(item, index) in data.website.diagnostics.details.items[0]">
	<span class="lu-label lu-label--warning lu-label--status"></span>
	<span class="col-md-5 m-0 p-0">{literal}{{ index }}{/literal}:
		 {literal}{{ item }}{/literal}
	</span>
</li>