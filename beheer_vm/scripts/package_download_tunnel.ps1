param($vm, $user="leenfiets")
ssh -R 1080:us.archive.ubuntu.com:80 $user@$vm