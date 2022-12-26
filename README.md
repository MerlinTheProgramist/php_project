# php project
Twitter alternative, who knows?

```bash
docker-compose up -d --build
```

### Rebuild and restart database 
```bash
docker-compose down --rmi all
```

### Enter mariaDB shell 
```
docker exec -it php_project-db-1 bash -c "mysql --user=mysql_user --password=mysql_pass"
```