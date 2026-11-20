@echo off
REM Mengunduh gambar desain Figma ke public\img (tautan Figma berlaku 7 hari sejak dibuat)
if not exist public\img mkdir public\img
curl -L -s -o public\img\hero-bg.png https://www.figma.com/api/mcp/asset/71b9a6f3-4545-4040-aff5-dc4661b70b14/044e7.png
curl -L -s -o public\img\astronaut.png https://www.figma.com/api/mcp/asset/71b9a6f3-4545-4040-aff5-dc4661b70b14/3acd3.png
curl -L -s -o public\img\cloud.png https://www.figma.com/api/mcp/asset/71b9a6f3-4545-4040-aff5-dc4661b70b14/7bbe3.png
curl -L -s -o public\img\about.png https://www.figma.com/api/mcp/asset/71b9a6f3-4545-4040-aff5-dc4661b70b14/c534d.png
curl -L -s -o public\img\subject-biologi.png https://www.figma.com/api/mcp/asset/71b9a6f3-4545-4040-aff5-dc4661b70b14/5dc7b.png
curl -L -s -o public\img\subject-fisika.png https://www.figma.com/api/mcp/asset/71b9a6f3-4545-4040-aff5-dc4661b70b14/43d99.png
curl -L -s -o public\img\subject-kimia.png https://www.figma.com/api/mcp/asset/71b9a6f3-4545-4040-aff5-dc4661b70b14/87fd7.png
curl -L -s -o public\img\subject-astronomi.png https://www.figma.com/api/mcp/asset/71b9a6f3-4545-4040-aff5-dc4661b70b14/37852.png
curl -L -s -o public\img\subject-matematika.png https://www.figma.com/api/mcp/asset/71b9a6f3-4545-4040-aff5-dc4661b70b14/2968d.png
curl -L -s -o public\img\auth-login.png https://www.figma.com/api/mcp/asset/70997b42-196b-4cc1-8d2a-c36d08465524/9f9d6.png
curl -L -s -o public\img\login-stt.png https://www.figma.com/api/mcp/asset/70997b42-196b-4cc1-8d2a-c36d08465524/ef6c2.png
curl -L -s -o public\img\login-ml1.png https://www.figma.com/api/mcp/asset/70997b42-196b-4cc1-8d2a-c36d08465524/3379f.png
curl -L -s -o public\img\login-ml2.png https://www.figma.com/api/mcp/asset/70997b42-196b-4cc1-8d2a-c36d08465524/08e6d.png
curl -L -s -o public\img\login-warna.png https://www.figma.com/api/mcp/asset/70997b42-196b-4cc1-8d2a-c36d08465524/ec3f4.png
curl -L -s -o public\img\auth-register.png https://www.figma.com/api/mcp/asset/f0c61034-e532-40b0-a365-1dbec543a3f4/cf043.png
curl -L -s -o public\img\reg-kl.png https://www.figma.com/api/mcp/asset/f0c61034-e532-40b0-a365-1dbec543a3f4/a6039.png
curl -L -s -o public\img\reg-kc.png https://www.figma.com/api/mcp/asset/f0c61034-e532-40b0-a365-1dbec543a3f4/a217f.png
curl -L -s -o public\img\reg-kbs.png https://www.figma.com/api/mcp/asset/f0c61034-e532-40b0-a365-1dbec543a3f4/3d5d5.png
curl -L -s -o public\img\content-bg.png https://www.figma.com/api/mcp/asset/d9799810-3e33-4a17-89f3-88455a08ee93/ce769.png
curl -L -s -o public\img\medal.png https://www.figma.com/api/mcp/asset/d9799810-3e33-4a17-89f3-88455a08ee93/ab748.png
curl -L -s -o public\img\star.png https://www.figma.com/api/mcp/asset/d9799810-3e33-4a17-89f3-88455a08ee93/d92bb.png
curl -L -s -o public\img\orbit.png https://www.figma.com/api/mcp/asset/d9799810-3e33-4a17-89f3-88455a08ee93/b944c.png
curl -L -s -o public\img\card-astronomi.png https://www.figma.com/api/mcp/asset/ebf18dba-22e4-4a33-91aa-bd2241a1c177/bc97a.png
curl -L -s -o public\img\card-biologi.png https://www.figma.com/api/mcp/asset/ebf18dba-22e4-4a33-91aa-bd2241a1c177/2c21c.png
curl -L -s -o public\img\card-fisika.png https://www.figma.com/api/mcp/asset/ebf18dba-22e4-4a33-91aa-bd2241a1c177/094f4.png
curl -L -s -o public\img\sim-bg.png https://www.figma.com/api/mcp/asset/4d56d2ea-35b4-4207-9791-5e5d5d61d38e/dfbf5.png
curl -L -s -o public\img\stopwatch.png https://www.figma.com/api/mcp/asset/773632e1-e8fb-4817-8258-0975ce24fd47/ef2b7.png
echo Selesai. Daftar file di public\img:
dir /b public\img
pause
