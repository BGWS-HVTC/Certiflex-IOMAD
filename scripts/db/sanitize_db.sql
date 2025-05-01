UPDATE public.mdl_user
SET email = 'test_' || email;

UPDATE public.mdl_config
SET value = ''
WHERE id in (
    425, --smtphosts
    427, --smtpuser
    428 --smtppass
);

UPDATE public.mdl_config
SET value = '' -- This is what will do ONCE SSO is properly setup --'https://cg-dev-ipscode.com/auth/saml/index.php'
WHERE id = 356;

-- chat_serverhost
UPDATE public.mdl_config
SET value = 'cg-dev-ipscode.com'
WHERE id = 376;

--
-- UPDATE cg_dev.public.mdl_block_instances
-- SET configdata = encode(REPLACE(convert_from(decode(configdata, 'base64'), 'UTF8'), 'https://www.compassmylms.com', 'https://www.cg-lms-dev.ipscode.com')::bytea, 'base64');
--
-- (SELECT id, blockname, configdata, convert_from(decode(configdata, 'base64'), 'UTF8') as decoded_config_data
-- FROM mdl_block_instances)