{% if shared_content_blocks.length > 0 %}
    <aside class="{{ side_class }}">
        <ol class="shared_content">
        {% for block in shared_content_blocks %}
            <li>
                {{ shared_content_blocks }}
            </li>
        {% endfor %}
        </ol>
    </aside>
{% endif %}