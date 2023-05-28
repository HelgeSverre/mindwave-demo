#!/bin/sh

# See: https://laracasts.com/discuss/channels/laravel/minio-programatically-create-bucket-sail?page=1&replyId=844795

/usr/bin/mc config host add local ${AWS_ENDPOINT} ${AWS_ACCESS_KEY_ID} ${AWS_SECRET_ACCESS_KEY};
/usr/bin/mc rm -r --force local/${AWS_BUCKET};
/usr/bin/mc mb -p local/${AWS_BUCKET};
/usr/bin/mc policy set download local/${AWS_BUCKET};
/usr/bin/mc policy set public local/${AWS_BUCKET};
/usr/bin/mc anonymous set upload local/${AWS_BUCKET};
/usr/bin/mc anonymous set download local/${AWS_BUCKET};
/usr/bin/mc anonymous set public local/${AWS_BUCKET};

exit 0;
