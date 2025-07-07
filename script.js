<script>
    function loadHTML(targetElementId, htmlFilePath) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', htmlFilePath, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById(targetElementId).innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    loadHTML('mens-collection-placeholder', "Men's Collection.html");
    loadHTML('womens-collection-placeholder', "Women's Collection.html");
    loadHTML('kids-collection-placeholder', 'Kids Collection.html');
</script>
