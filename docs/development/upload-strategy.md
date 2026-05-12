# Upload Strategy

- Upload validation must use Form Requests.
- Upload rules must use `config/uploads.php`.
- File saving/deleting must use `FileUploadService`.
- Controllers should only call the service.
- Do not hardcode mime types, max sizes, or paths.