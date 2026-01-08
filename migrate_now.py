import subprocess
import os

os.chdir(r"d:\UGSF sem 6\Main\training-placement")

print("=" * 50)
print("RUNNING DATABASE MIGRATION")
print("=" * 50)
print()

result = subprocess.run(
    ["php", "artisan", "migrate", "--force"],
    capture_output=True,
    text=True
)

print(result.stdout)
if result.stderr:
    print(result.stderr)

if result.returncode == 0:
    print()
    print("✓ Migration completed successfully!")
    print("The form_responses table has been created.")
else:
    print()
    print(f"✗ Migration failed with code: {result.returncode}")

print()
input("Press Enter to close...")
