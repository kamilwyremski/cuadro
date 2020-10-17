
{% if boxes %}
  {% for box in boxes %}
    {% if box.type=='text' %}
      <div class="box">
        {{ box.content|raw }}
      </div>
    {% elseif box.type=='statistic' %}
      <div class="box">
        <h5>{{ 'Statistics'|lang }}</h5>
        <table class="striped">
          <tr><td>{{ 'Files'|lang }}:</td><td>{{ box.statistic.files }}</td></tr>
          <tr><td>{{ 'In Waiting Room'|lang }}:</td><td>{{ box.statistic.files_waiting_room }}</td></tr>
          <tr><td>{{ 'Categories'|lang }}:</td><td>{{ box.statistic.categories }}</td></tr>
          <tr><td>{{ 'Tags'|lang }}:</td><td>{{ box.statistic.tags }}</td></tr>
          <tr><td>{{ 'Users'|lang }}:</td><td>{{ box.statistic.users }}</td></tr>
        </table>
      </div>
    {% elseif box.type=='categories' %}
      {% if box.categories %}
        <div class="box">
          <h5>{{ 'Categories'|lang }}</h5>
          <ul class="collection">
            {% for item in box.categories %}
              <li class="collection-item"><a href="{{ path('category',item.id,item.slug) }}" title="{{ item.name }}">{{ item.name }}</a></li>
            {% endfor %}
          </ul>
        </div>
      {% endif %}
    {% elseif box.type=='tags' %}
      {% if box.tags %}
        <div class="box">
          <h5>{{ 'Tags'|lang }}</h5>
          <p class="center-align">
            {% for item in box.tags %}
              <a href="{{ path('tag',item.id,item.slug) }}" title="{{ item.name }}" style="font-size: {{ (item.amount/box.max_amount_tag)*15+7 }}px">{{ item.name }}</a>
            {% endfor %}
          </p>
        </div>
      {% endif %}
    {% elseif box.type=='new_files' or box.type=='waiting_room' or box.type=='random_files' or box.type=='top_files' %}
      {% if box.files %}
        <div class="box">
          <h5>{% if box.type=='new_files' %}{{ 'New'|lang }}{% elseif box.type=='waiting_room' %}{{ 'Waiting Room'|lang }}{% elseif box.type=='random_files' %}{{ 'Random'|lang }}{% elseif  box.type=='top_files' %}{{ 'Top'|lang }}{% endif %}</h5>
          {% for item in box.files %}
          <div class="card hoverable">
            <div class="card-image">
              {% if item.type=='image' %}
                <a href="{{ path('file',item.id,item.slug) }}" title="{{ item.title }}"><img src="upload/files/{{ item.thumb }}" alt="{{ item.title }}" class="responsive-img"></a>
              {% elseif item.type=='iframe' %}
                <a href="{{ path('file',item.id,item.slug) }}" title="{{ item.title }}"><img src="{{ item.thumb }}" alt="{{ item.title }}" class="responsive-img"></a>
              {% elseif item.type=='video' %}
                <video class="responsive-video" controls>
                  <source src="upload/files/{{ item.url }}" type="video/mp4">
                </video>
              {% endif %}
            </div>
		    <div class="card-content">
			  <h6><a href="{{ path('file',item.id,item.slug) }}" title="{{ item.title }}" class="truncate">{{ item.title }}</a></h6>
		    </div>
          </div>
          {% endfor %}
        </div>
      {% endif %}
    {% elseif box.type=='search_box' %}
      <div class="box">
        <form action="{{ path('search') }}" method="get" class="box_search">
          <h5>{{ 'Search'|lang }}</h5>
          <div class="input-field">
            <input name="q" type="search" required value="{{ search }}">
            <label class="label-icon" for="q"><i class="material-icons">search</i></label>
          </div>
        </form>
      </div>
    {% endif %}
  {% endfor %}
{% endif %}
