<li class="lu-list__item" v-if="data.website.pageStats.fcp">
	<span class="lu-list__item-title">{$MGLANG->T('firstContentfulPaint')}</span>
	<span class="lu-list__value" >{literal}{{ data.website.pageStats.fcp.score }}{/literal} s
	</span>
</li>

{* <li class="lu-list__item" v-if="data.website.pageStats.lcpe">
	<span class="lu-list__item-title">{$MGLANG->T('largestContentfulPaintElement')}</span>
	<span class="lu-list__value" >{literal}{{ data.website.pageStats.lcpe.score }}{/literal} s
	</span>
</li> *}

<li class="lu-list__item" v-if="data.website.pageStats.tbt">
	<span class="lu-list__item-title">{$MGLANG->T('totalBlockingTime')}</span>
	<span class="lu-list__value" >{literal}{{ data.website.pageStats.tbt.score }}{/literal} s
	</span>
</li>

<li class="lu-list__item" v-if="data.website.pageStats.ds">
	<span class="lu-list__item-title">{$MGLANG->T('doomSize')}</span>
	<span class="lu-list__value" >{literal}{{ data.website.pageStats.ds.score }}{/literal} s
	</span>
</li>

<li class="lu-list__item" v-if="data.website.pageStats.si">
	<span class="lu-list__item-title">{$MGLANG->T('speedIndex')}</span>
	<span class="lu-list__value" >{literal}{{ data.website.pageStats.si.displayValue }}{/literal}
	</span>
</li>

<li class="lu-list__item" v-if="data.website.pageStats.interactive">
	<span class="lu-list__item-title">{$MGLANG->T('interactive')}</span>
	<span class="lu-list__value" >{literal}{{ data.website.pageStats.interactive.displayValue }}{/literal}
	</span>
</li>

<li class="lu-list__item" v-if="data.website.pageStats.cls">
	<span class="lu-list__item-title">{$MGLANG->T('cumulativeLayoutShift')}</span>
	<span class="lu-list__value" >{literal}{{ data.website.pageStats.cls.displayValue }}{/literal}
	</span>
</li>