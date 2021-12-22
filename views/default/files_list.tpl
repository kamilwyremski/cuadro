
{% if files %}
  {% for file in files %}
    <div class="card horizontal files_list hoverable">
      <div class="card-image">
        {% if file.type=='image' %}
          <a href="{{ path('file',file.id,file.slug) }}" title="{{ file.title }}"><img src="upload/files/{{ file.thumb }}" alt="{{ file.title }}" class="responsive-img" loading="lazy"></a>
        {% elseif file.type=='iframe' %}
          <a href="{{ path('file',file.id,file.slug) }}" title="{{ file.title }}"><img src="{{ file.thumb }}" alt="{{ file.title }}" class="responsive-img" loading="lazy"></a>
        {% elseif file.type=='video' %}
          <video class="responsive-video" controls>
            <source src="upload/files/{{ file.url }}" type="video/mp4">
          </video>
        {% endif %}
      </div>
      <div class="card-stacked">
        <div class="card-content">
          <h5><a href="{{ path('file',file.id,file.slug) }}" title="{{ file.title }}">{{ file.title }}</a></h5>
          <p>{{ 'Added'|lang }}: {{ file.date|date('Y-m-d') }}</p>
          {% if controller=='my_files' %}
            <br>
            <a class="waves-effect waves-light btn" href="{{ path('edit',file.id,file.slug) }}">{{ 'Edit'|lang }}</a>
            <a class="waves-effect waves-light btn modal-trigger" href="#modal_remove_{{ file.id }}">{{ 'Remove'|lang }}</a>
            <div id="modal_remove_{{ file.id }}" class="modal">
              <form method="post">
                <input type="hidden" name="action" value="remove_file">
                <input type="hidden" name="id" value="{{ file.id }}">
                <div class="modal-content">
                    <h4>{{ 'Delete file'|lang }}: {{ file.title }}</h4>
                    <p>{{ 'Are you sure you want to delete file'|lang }}: "{{ file.title }}"?</p>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect btn-flat">{{ 'Cancel'|lang }}</a>
                    <button type="submit" class="btn waves-effect btn-flat red">{{ 'Execute'|lang }}</button>
                </div>
              </form>
            </div>
          {% endif %}
        </div>
      </div>
    </div>
  {% endfor %}
{% endif %}
     