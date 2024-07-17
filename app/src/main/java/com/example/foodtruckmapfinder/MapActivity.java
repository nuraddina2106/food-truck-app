package com.example.foodtruckmapfinder;

import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.drawable.Drawable;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.app.ActivityCompat;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;
import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.BitmapDescriptor;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;
import com.squareup.picasso.Picasso;
import com.squareup.picasso.Target;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

public class MapActivity extends AppCompatActivity implements OnMapReadyCallback {

    private GoogleMap mMap;
    private EditText searchBar;
    private FusedLocationProviderClient fusedLocationClient;
    private static String URL = "http://192.168.0.100/food_truck_mapper/get_food_trucks.php";
    private static final int LOCATION_PERMISSION_REQUEST_CODE = 1000;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_map);

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Food Truck Finder");

        toolbar.inflateMenu(R.menu.main_menu);

        toolbar.setOnMenuItemClickListener(item -> {
            int itemId = item.getItemId();
            if (itemId == R.id.action_home) {
                startActivity(new Intent(MapActivity.this, MainActivity.class));
                return true;
            } else if (itemId == R.id.action_map) {
                showToast("You're at map");
                return true;
            } else if (itemId == R.id.action_about_us) {
                startActivity(new Intent(MapActivity.this, AboutUsActivity.class));
                return true;
            }
            return false;
        });

        searchBar = findViewById(R.id.search_bar);
        Button zoomInButton = findViewById(R.id.zoom_in);
        Button zoomOutButton = findViewById(R.id.zoom_out);

        fusedLocationClient = LocationServices.getFusedLocationProviderClient(this);

        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);

        zoomInButton.setOnClickListener(v -> mMap.animateCamera(CameraUpdateFactory.zoomIn()));
        zoomOutButton.setOnClickListener(v -> mMap.animateCamera(CameraUpdateFactory.zoomOut()));

        searchBar.setOnEditorActionListener((v, actionId, event) -> {
            String query = searchBar.getText().toString().trim();
            if (!TextUtils.isEmpty(query)) {
                searchFoodTrucks(query);
            }
            return false;
        });

        // Fetch IP address and construct URL
        fetchIPAddress();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main_menu, menu);
        return true;
    }

    @Override
    public void onMapReady(@NonNull GoogleMap googleMap) {
        mMap = googleMap;
        fetchFoodTrucks();
        enableMyLocation();
    }

    private void enableMyLocation() {
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED &&
                ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, LOCATION_PERMISSION_REQUEST_CODE);
        } else {
            mMap.setMyLocationEnabled(true);
        }
    }

    private void fetchIPAddress() {
        new FetchIPTask().execute("https://api.ipify.org?format=json");
    }

    private class FetchIPTask extends AsyncTask<String, Void, String> {
        @Override
        protected String doInBackground(String... urls) {
            String ip = null;
            try {
                URL url = new URL(urls[0]);
                HttpURLConnection urlConnection = (HttpURLConnection) url.openConnection();
                BufferedReader in = new BufferedReader(new InputStreamReader(urlConnection.getInputStream()));
                StringBuilder response = new StringBuilder();
                String inputLine;
                while ((inputLine = in.readLine()) != null) {
                    response.append(inputLine);
                }
                in.close();
                JSONObject jsonObject = new JSONObject(response.toString());
                ip = jsonObject.getString("ip");
            } catch (Exception e) {
                e.printStackTrace();
            }
            return ip;
        }

        @Override
        protected void onPostExecute(String ip) {
            if (ip != null) {
                URL = "http://" + ip + "/food_truck_mapper/get_food_trucks.php";
                fetchFoodTrucks();
            } else {
                showToast("Failed to retrieve IP address.");
            }
        }
    }

    private void fetchFoodTrucks() {
        RequestQueue queue = Volley.newRequestQueue(this);
        JsonArrayRequest jsonArrayRequest = new JsonArrayRequest(Request.Method.GET, URL, null,
                response -> {
                    try {
                        Log.d("FetchFoodTrucks", "Response received: " + response.toString());
                        mMap.clear(); // Clear previous markers
                        for (int i = 0; i < response.length(); i++) {
                            JSONObject foodTruck = response.getJSONObject(i);
                            String name = foodTruck.getString("name");
                            String businessType = foodTruck.getString("business_type");
                            String operatorName = foodTruck.getString("operator_name");
                            String address = foodTruck.getString("address");
                            String businessHours = foodTruck.getString("business_hours");
                            double latitude = foodTruck.getDouble("latitude");
                            double longitude = foodTruck.getDouble("longitude");

                            LatLng location = new LatLng(latitude, longitude);
                            MarkerOptions markerOptions = new MarkerOptions()
                                    .position(location)
                                    .title(name)
                                    .snippet(businessType + "\n" + operatorName + "\n" + address + "\n" + businessHours)
                                    .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_BLUE));
                            mMap.addMarker(markerOptions);

                            if (i == 0) {
                                mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(location, 10));
                            }
                        }

                        mMap.setOnInfoWindowClickListener(marker -> {
                            // Handle marker click here
                        });
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                }, error -> {
            Log.e("FetchFoodTrucks", "Error fetching food trucks: " + error.toString());
            Toast.makeText(MapActivity.this, "Error fetching food trucks", Toast.LENGTH_SHORT).show();
        });

        queue.add(jsonArrayRequest);
    }


    private void searchFoodTrucks(String query) {
        // Implement search logic if needed
        showToast("Searching for: " + query);
    }

    private void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);

        if (requestCode == LOCATION_PERMISSION_REQUEST_CODE) {
            if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                enableMyLocation();
            } else {
                showToast("Location permission denied. Cannot show current location on the map.");
            }
        }
    }
}
