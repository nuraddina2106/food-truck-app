package com.example.foodtruckmapfinder;

import android.content.Context;
import android.graphics.Bitmap;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.ImageRequest;

import java.util.List;

public class MenuAdapter extends RecyclerView.Adapter<MenuAdapter.ViewHolder> {
    private Context context;
    private List<MenuItem> menuItems;
    private RequestQueue queue;

    public MenuAdapter(Context context, List<MenuItem> menuItems, RequestQueue queue) {
        this.context = context;
        this.menuItems = menuItems;
        this.queue = queue;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.activity_menu, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        MenuItem menuItem = menuItems.get(position);
        holder.titleTextView.setText(menuItem.getName());
        holder.subtitleTextView.setText(menuItem.getDescription() + " - RM " + menuItem.getPrice());
        loadImage(menuItem.getImage(), holder.imageView);
    }

    @Override
    public int getItemCount() {
        return menuItems.size();
    }

    private void loadImage(String imageUrl, final ImageView imageView) {
        ImageRequest imageRequest = new ImageRequest(imageUrl,
                response -> imageView.setImageBitmap(response),
                0, 0, ImageView.ScaleType.CENTER_CROP, Bitmap.Config.RGB_565,
                error -> {
                    // Handle error loading image if needed
                    // No placeholder image set here, leaving imageView unchanged
                });

        queue.add(imageRequest);
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        ImageView imageView;
        TextView titleTextView;
        TextView subtitleTextView;

        public ViewHolder(View itemView) {
            super(itemView);
            imageView = itemView.findViewById(R.id.menu_image);
            titleTextView = itemView.findViewById(R.id.menu_name);
            subtitleTextView = itemView.findViewById(R.id.menu_desc);
        }
    }
}
