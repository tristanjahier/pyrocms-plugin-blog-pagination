# PyroCMS Blog Pagination plugin

"Blog Pagination" is a *PyroCMS* plugin that provides methods to retrieve pagination information of a blog post.

Created by [Tristan Jahier](http://tristan-jahier.fr).

## Compatibility

- PyroCMS: `2.2`

## Usage

### Page number

Retrieves the number of the page where the blog post is. Starts at 1.

- @param post_id : ID of the blog post
- @return integer

    {{ blog_pagination:page_number post_id="6" }}

### Page offset

Retrieves the offset of the page where the blog post is. This offset equals the total number of posts in the previous pages.

- @param post_id : ID of the blog post
- @return integer

    {{ blog_pagination:page_offset post_id="7" }}

### Page URI

Generates an URI that refers to the page where the blog post is, based on the `page_offset` method (ex: `/page/20`). Returns nothing if it is the first page.

- @param post_id : ID of the blog post
- @return string

    {{ blog_pagination:page_uri post_id="8" }}

### Post index

Retrieves the position of the blog post among all paginated posts.

- @param post_id : ID of the blog post
- @return integer

    {{ blog_pagination:post_index post_id="9" }}

## Example

If you want to make a back link to the posts list from a single post view, you may use this:

```html
{{ post }}

    <a href="blog{{ blog_pagination:page_uri post_id=id }}" title="Return to posts list">← Return to posts list</a>

    ...
{{ /post }}
```

If you blog post is at the *third page*, with *10 items per page*, it will generate a return link with a URL like that : `yoursite.com/blog/page/20`.