# Video

https://www.youtube.com/watch?v=T5NlVcsy-8w&t=3s

# Streetrades
Is location based marketplace 3 tier web applications for users to buy or sell goods with others within close geographic proximity.

Features:
- Login User
- Register User
- Post/Upload items to sell
- Update items to sell
- Delete items on sell
- Sort items based on close geographic proximity
- Chat between users (buggy)

# AWS Resources

S3 Bucket: Created a private S3 bucket to host static front end code and store files.

Cloudfront: Configured origin access identity so that cloudfront could access code and other files from the S3 bucket created.  Here is the distribution domain for testing purposes https://djqcchjm8l4oz.cloudfront.net/

Lambda: Setup lambda function for api response.

DynamoDB: It is set up to contain share metadata and information about the files.

Cognito: created a user pool and identity pool intended to incorporate facbook/google authentication/authorization for the web application.

CI/CD Pipeline: Set up code commit and pushed my code there (static front end React JS code for testing).  Also set up the pipeline to connect to code build and then deployment.  The pipeline failed and crashed in code build, im still troubleshooting but I think the build.yml file is wrong.
