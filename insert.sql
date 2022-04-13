INSERT INTO `api`.login_appclient(client_identifier, client_secret, redirect_uri) VALUES ('web', '847cbf45-a364-4690-9dd8-98a423e30b6a', null);

INSERT INTO `api`.login_scope(scope, description) VALUES (1, 'Manager users');

INSERT INTO `api`.login_scope_appclient(scope_id, appclient_id) VALUES (1, 1);
