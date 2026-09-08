# PicturePortal

## Description ##

You're an avid digital photographer with a large collection of well managed and tagged
photos that you want to filter and view through a sleek mobile-first web UI? 
And you use DigiKam to manage your collection and tags?

You're in luck!

PicturePortal is a self-hosted Flickr clone (from before it went overly commercial) with two main use cases:

- A mobile-first web interface into your photo collection that allows you to apply filters based on the tags that you've created and managed through DigiKam, and browse your photo collection through your browser.
- With appropriate NAT rules in place, you can apply a "Public" tag to selected photos which are then made available for the public to browse and filter
  - The development roadmap includes Oauth2 authentication (through a Google account, etc) to then use RBACs to manage tag visibility for selected users and groups.

**Technology stack**: 
  - PHP
  - Laravel
  - Vue3
  - Javascript

**Status**:  
  - Alpha

**Demo instance:**

- https://digikamweb.base10.webdevops.uk

**Screenshot**:

![](https://raw.githubusercontent.com/m-a-t-t-h/picture-portal/main/public/docs/screenshot-1.png)

## Dependencies

- A photo collection managed using DigiKam
- A locally installed web server

## Installation

To follow

## Configuration

To follow

## Known issues

- Documented at https://github.com/m-a-t-t-h/picture-portal/issues

## Getting help

If you have questions, concerns, bug reports, etc, please file an issue in this repository's Issue Tracker.

## Getting involved

Human-generated submissions are welcome! PRs wholly or largely constructed by LLMs will be rejected.

Fork this repository, do your work, and submit a pull request or send a mail to pictureportal@webdevops.uk.

## Found a security issue?

Please do not report security vulnerabilities through public GitHub issues, discussions, or pull requests.

Instead, report privately through email:

security@webdevops.uk 

Please include:

* A description of the vulnerability and its potential impact.
* Step-by-step reproduction instructions or a proof-of-concept.
* The component, file, or endpoint affected (and version/commit).
* Any logs, screenshots, or payloads that demonstrate the issue.
* Your suggested remediation, if you have one.

## Licence

GPL-3.0-only
