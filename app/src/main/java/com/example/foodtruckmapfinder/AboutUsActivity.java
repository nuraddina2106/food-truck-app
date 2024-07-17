package com.example.foodtruckmapfinder;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.Toast;

public class AboutUsActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_about_us);

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("About Us");

        toolbar.inflateMenu(R.menu.main_menu);

        // Set up the Toolbar's menu item click listener
        toolbar.setOnMenuItemClickListener(new Toolbar.OnMenuItemClickListener() {
            @Override
            public boolean onMenuItemClick(MenuItem item) {
                int itemId = item.getItemId();
                if (itemId == R.id.action_home) {  // Navigate to MainActivity
                    startActivity(new Intent(AboutUsActivity.this, MainActivity.class));
                    return true;
                } else if (itemId == R.id.action_map) {  // Navigate to MapActivity
                    startActivity(new Intent(AboutUsActivity.this, MapActivity.class));
                    return true;
                } else if (itemId == R.id.action_about_us) {  // Already in AboutUsActivity
                    showToast("You're at About Us page");
                    return true;
                }
                return false;
            }
        });

        Button button1 = findViewById(R.id.button1);
        button1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Open GitHub URL when the button is clicked
                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("https://github.com/nuraddina2106/food-truck-app"));
                startActivity(intent);
            }
        });
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main_menu, menu);
        return true;
    }

    private void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }

}
