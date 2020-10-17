
{% if pagination.page_count %}
	<div class="center-align">
		<ul class="pagination">
			<li class="waves-effect {% if pagination.page_number==1 %}disabled return_false{% endif %}"><a href="{% if controller_subpage %}{{ path(controller_subpage) }}{% else %}{{ path(controller) }}{% endif %}{% if pagination.page_url.page %}?{% endif %}{{ pagination.page_url.page }}" title="{{ 'First page'|lang }}"><i class="material-icons">chevron_left</i></a></li>
			{% for this_page in pagination.page_start..pagination.page_count %}
				{% if loop.index0<10 %}
					<li class="waves-effect {% if pagination.page_number==this_page %}disabled return_false active{% endif %}"><a href="{% if controller_subpage %}{{ path(controller_subpage) }}{% else %}{{ path(controller) }}{% endif %}?{{ pagination.page_url.page }}{% if pagination.page_url.page %}&{% endif %}page={{ this_page }}" title="{{ 'Page'|lang }}: {{ this_page }}">{{ this_page }}</a></li>
				{% endif %}
			{% endfor %}
		   <li class="waves-effect {% if pagination.page_number==pagination.page_count %}disabled return_false{% endif %}"><a href="{% if controller_subpage %}{{ path(controller_subpage) }}{% else %}{{ path(controller) }}{% endif %}?{{ pagination.page_url.page }}{% if pagination.page_url.page %}&{% endif %}page={{ pagination.page_count }}" title="{{ 'Last page'|lang }}"><i class="material-icons">chevron_right</i></a></a></li>
		</ul>
	</div>
{% endif %}