
{% if alert_success %}
  {% for alert in alert_success %}
    <div class="card-panel green darken-1 white-text">{{ alert }}</div>
  {% endfor %}
{% endif %}
{% if alert_success or alert_danger %}
  <div id="js_scroll_page"></div>
{% endif %}
{% if alert_danger %}
  {% for alert in alert_danger %}
	 <div class="card-panel red darken-1 white-text">{{ alert }}</div>
  {% endfor %}
{% endif %}
