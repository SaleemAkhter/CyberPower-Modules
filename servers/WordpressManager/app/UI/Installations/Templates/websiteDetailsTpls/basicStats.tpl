<li class="lu-list__item" v-if="data.website.id">
    <span class="lu-list__item-title">{$MGLANG->T('WebsiteUrl')}</span>
    <span class="lu-list__value"><a style="word-break: break-all;"  {literal}  :href=" data.website.id"  {/literal}  target="_blank">{literal}{{ data.website.id }}{/literal}</a></span>
</li>


<li class="lu-list__item" v-if="data.website.loadingExperience && data.website.loadingExperience.overall_category">
    <span class="lu-list__item-title">{$MGLANG->T('loadingExperienceRating')}</span>
    <span class="lu-list__value">{literal}{{ data.website.loadingExperience.overall_category }}{/literal}</span>
</li>

<li class="lu-list__item" v-if="data.website.loadingExperience && data.website.loadingExperience.metrics">
    <span class="lu-list__item-title">{$MGLANG->T('firstContentfulPaint')}</span>
    <span class="lu-list__value" v-if="data.website.loadingExperience.metrics.FIRST_CONTENTFUL_PAINT_MS.percentile">
	{literal}{{ data.website.loadingExperience.metrics.FIRST_CONTENTFUL_PAINT_MS.percentile }}{/literal} ms - category:
                                {literal}{{ data.website.loadingExperience.metrics.FIRST_CONTENTFUL_PAINT_MS.category }}{/literal}</span>
    <span class="lu-list__value" v-else>-</span>

</li>

<li class="lu-list__item" v-if="data.website.loadingExperience && data.website.loadingExperience.metrics.FIRST_INPUT_DELAY_MS">
    <span class="lu-list__item-title">{$MGLANG->T('firstInputDelay')}</span>
    <span class="lu-list__value" v-if="data.website.loadingExperience.metrics.FIRST_INPUT_DELAY_MS.percentile">{literal}{{ data.website.loadingExperience.metrics.FIRST_INPUT_DELAY_MS.percentile }}{/literal} ms - category:
                                {literal}{{ data.website.loadingExperience.metrics.FIRST_INPUT_DELAY_MS.category }}{/literal}</span>
                                <span class="lu-list__value" v-else>-</span>
</li>

<li class="lu-list__item" v-if="data.website.loadingExperience && data.website.loadingExperience.metrics.LARGEST_CONTENTFUL_PAINT_MS">
    <span class="lu-list__item-title">{$MGLANG->T('largestContentfulPaint')}</span>
    <span class="lu-list__value" v-if="data.website.loadingExperience.metrics.LARGEST_CONTENTFUL_PAINT_MS.percentile">>{literal}{{ data.website.loadingExperience.metrics.LARGEST_CONTENTFUL_PAINT_MS.percentile }}{/literal} ms - category:
                                {literal}{{ data.website.loadingExperience.metrics.LARGEST_CONTENTFUL_PAINT_MS.category }}{/literal}</span>
                                <span class="lu-list__value" v-else>-</span>
</li>

<li class="lu-list__item" v-if="data.website.loadingExperience && data.website.loadingExperience.metrics.CUMULATIVE_LAYOUT_SHIFT_SCORE">
    <span class="lu-list__item-title">{$MGLANG->T('cumulativeLayoutShiftScore')}</span>
    <span class="lu-list__value" v-if=" data.website.loadingExperience.metrics.CUMULATIVE_LAYOUT_SHIFT_SCORE.percentile">{literal}{{ data.website.loadingExperience.metrics.CUMULATIVE_LAYOUT_SHIFT_SCORE.percentile }}{/literal} ms - category:
                                {literal}{{ data.website.loadingExperience.metrics.CUMULATIVE_LAYOUT_SHIFT_SCORE.category }}{/literal}</span>
                                <span class="lu-list__value" v-else>-</span>
</li>
