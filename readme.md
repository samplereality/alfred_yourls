Shorten a URL with your self-hosted [YOURLS](https://yourls.org) instance from Alfred, then copy the short URL to your clipboard.

### [Download Workflow](https://github.com/samplereality/alfred_yourls/raw/main/Shorten%20URL%20with%20YOURLS.alfredworkflow)

![](https://i.imgur.com/6boCXFC.gif)

### Pre-req

1. Install PHP: `brew install php`

### Setup

1. Double-click the downloaded workflow to import it into Alfred.
2. Fill out the configuration settings:
   - **Domain**: your YOURLS installation, without `http://` or `https://` (e.g. `urls.example.com`)
   - **Signature**: your secure signature token, found in your YOURLS admin under **Tools**
3. Done.

### Usage

The workflow uses the keyword `y` (you can change it in Alfred).

| You type | What happens |
| --- | --- |
| `y` | Shortens the URL on your clipboard with a random keyword |
| `y test` | Shortens the clipboard URL as `yourdomain/test` |
| `y *test` | Same as above |
| `y example.com` | Shortens the URL you typed |
| `y example.com*test` | Shortens the URL you typed as `yourdomain/test` |

A bare word with no dot in it is treated as a custom keyword for the clipboard URL; anything containing a dot is treated as a URL. URLs without `http://` or `https://` get `https://` added.

The short URL is copied to your clipboard and shown in a notification. If something goes wrong (for example, the keyword is already taken), the notification shows YOURLS's own error message.

Allowed keyword characters depend on your YOURLS settings; by default that's lowercase letters and digits.

### Changes in 1.1

- **Fixed:** running `y` with nothing typed now shortens the clipboard URL, as the original description promised. Previously the script ignored the clipboard and sent an empty URL, which YOURLS rejected with `400 Bad Request`.
- **New:** set a custom keyword for the clipboard URL with `y test` or `y *test`.
- URLs that already start with `http://` are no longer rewritten.
- Custom keywords and URLs are properly encoded, so keywords with spaces or URLs with query strings work.
- Errors from YOURLS are reported in the notification instead of raw PHP warnings.

The script inside the workflow is also in [`src/shorten.php`](src/shorten.php) for easy reading.

> **Note:** Alfred's debug log shows the full API request, including your signature token. Don't post those logs publicly; if you do, reset your signature from the YOURLS **Tools** page.

### Credit

Forked from [deletosh/alfred_yourls](https://github.com/deletosh/alfred_yourls), which was based on the work of:

- https://github.com/smoitzheim/alfred_yourls
- https://gist.github.com/jazzsequence/95e40ec6a2a3faf9e04e
