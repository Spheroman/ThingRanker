
sh_c='sh -c'
if ! [ -x "$(command -v docker compose)" ];
  then
    echo docker not found, installing
    user="$(id -un 2>/dev/null || true)"
    if [ "$user" != 'root' ]; then
      if command_exists sudo; then
        sh_c='sudo -E sh -c'
      else
        echo Error: needs sudo privledges to run install script
        exit 1
      fi
    fi
    $sh_c apt-get update
    $sh_c apt-get install ca-certificates curl
    $sh_c install -m 0755 -d /etc/apt/keyrings
    $sh_c curl -fsSL https://download.docker.com/linux/debian/gpg -o /etc/apt/keyrings/docker.asc
    $sh_c chmod a+r /etc/apt/keyrings/docker.asc
    $sh_c echo \
      "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/debian \
      $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
      tee /etc/apt/sources.list.d/docker.list > /dev/null
    $sh_c apt-get update
    $sh_c apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
fi

if ! [ -e ./ThingRanker/compose.yaml ]
  then
    echo compose.yaml not found, fetching repo
    git fetch "https://github.com/Spheroman/ThingRanker.git"
    cd ThingRanker
    git checkout docker
  else
    cd ThingRanker
fi

docker compose up